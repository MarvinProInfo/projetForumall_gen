<?php

namespace App\Controller;

use App\Form\ModerateurType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/moderateur_{id<\d+>}', name: 'app_moderateur')]
    public function mod($id,UserRepository $repoUser,Request $request){
        $user = $repoUser->find($id);
        $form = $this->createForm(ModerateurType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $role = $form->get('roles')->getData();
        
             $roleTab = explode(' ',$role);
           
        //    dd($roleTab);

            $user->setRoles($roleTab);
            $repoUser->save($user,1);
            $pseudo = $user->getPseudo();
            $this->addFlash("success","$pseudo a pour rôle $role");
            return $this->redirectToRoute('app_accueil');
        
        }
        


        return $this->render('security/modAttrib.html.twig', [
            'user' => $user,
            "formMod" => $form->createView()
        ]);
    }

    #[Route(path: '/remove_user_{id<\d+>}', name: 'app_remove_user')]
    public function remove($id, UserRepository $repoUser){
        $user = $repoUser->find($id);
        $repoUser->remove($user,1);
        $this->addFlash("success","Utilisateur retiré");
        return $this->redirectToRoute('app_accueil');
    }

    #[Route(path: '/gestion_utilisateur', name: 'app_gestion_utilisateur')]
    public function allUtil(UserRepository $repoUser){
        $users = $repoUser->findAll();
        return $this->render('security/gestionUtil.html.twig', [
            'users' => $users,      
        ]);
    }

}
