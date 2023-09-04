<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExternalApiController extends AbstractController
{
    // #[Route('/external/api', name: 'app_external_api')]
    public function index(): Response
    {
        // return $this->render('external_api/index.html.twig', [
        //     'controller_name' => 'ExternalApiController',
        // ]);
    }
}
