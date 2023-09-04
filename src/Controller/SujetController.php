<?php

namespace App\Controller;

use DateTime;
use App\Entity\Sujet;
use App\Form\SujetType;
use App\Repository\SujetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SujetController extends AbstractController
{
    #[Route('/sujet', name: 'app_sujet')]
    public function listeSujet(SujetRepository $repoSujet): Response
    {
        $sujets = $repoSujet->findAll();
        return $this->render('sujet/allSujet.html.twig', [
            'sujets' => $sujets,
        ]);
    }

    #[Route('/sujet_new', name: 'app_sujet_new')]
    public function newSujet(SujetRepository $repoSujet,Request $request){

        $sujet = new Sujet();
        
        $form = $this->createForm(SujetType::class,$sujet);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            $createdAt = new DateTime('now');
            $sujet->setDateCréation($createdAt);
            $sujet->setDateEdit($createdAt);
            $sujet->setUtilisateur($this->getUser());
            $repoSujet->save($sujet,1);
            return $this->redirectToRoute('app_sujet');
        }

        return $this->render('sujet/creationIndex.html.twig', [
            'sujet'=>$sujet,
            'formSujet' =>$form->createView()
        ]);
    }

    #[Route('/sujet_update_{id<\d+>}', name: 'app_sujet_update')]
    public function updateSujet($id, SujetRepository $repoSujet,Request $request){

        $sujet = $repoSujet->find($id);

        $form = $this->createForm(SujetType::class,$sujet);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $createdAt = new DateTime('now');
            $sujet->setDateEdit($createdAt);
            $sujet->setUtilisateur($this->getUser());
            $repoSujet->save($sujet,1);
            return $this->redirectToRoute('app_sujet');
        }

        return $this->render('sujet/creationIndex.html.twig', [
            'formSujet' =>$form->createView()
        ]);
    }

    #[Route('/sujet_remove_{id<\d+>}', name: 'app_sujet_remove')]
    public function removeSujet($id, SujetRepository $repoSujet){
        $sujet = $repoSujet->find($id);
        $repoSujet->remove($sujet,1);

        return $this->redirectToRoute('app_sujet');
    }

}
