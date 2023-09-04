<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\UserRepository;
use App\Repository\SujetRepository;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    #[Route('/message', name: 'app_message')]
    public function index(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }


    #[Route('/message_sujet_{id<\d+>}', name: 'app_sujet_message')]
    public function addMessage($id, MessageRepository $repoMessage,SujetRepository $repoSujet,UserRepository $repoUser, Request $request){

        $sujet = $repoSujet->find($id);
        $message = new Message();
        $messages = $repoMessage->findAll(['sujet_id' =>$id]);
        $user = new User();
        

        $form = $this->createForm(MessageType::class,$message);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $createdAt = new DateTime('now');
            
            $message->setDateCreation($createdAt);
            $message->setUtilisateur($this->getUser());
            $message->setSujet($sujet);
            
            $repoMessage->save($message,1);
        }
        return $this->render('message/message.html.twig', [
            'formMSG' => $form->createView(),
            'sujet' => $sujet,
            'message'=>$message,
            'messages'=>$messages,
            'user'=>$user
        ]);
    }
}
