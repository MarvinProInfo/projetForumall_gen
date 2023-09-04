<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SluggerInterface $slugger, UserRepository $userRepo): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $file = $form->get('avatar')->getData();
             $filename = $slugger->slug($user->getPseudo()).uniqid().'.'.$file->guessExtension();

             try {
                $file->move($this->getParameter('user_photos'),$filename);
             } catch (FileException $e) {
                //throw $th;
             }
             $user->setAvatar($filename);
             
            $createdAt = new DateTime('now');
            
            $user->setDateInscription($createdAt);

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
             

            $userRepo->save($user,1);
            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    
}
