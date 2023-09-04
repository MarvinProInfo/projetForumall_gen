<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(ProduitsRepository $repo, CategoriesRepository $repoCat): Response
    {

        $produits = $repo->findAll();
        $categories = $repoCat->findAll();

        return $this->render('accueil/index.html.twig', [
            'produits' => $produits,
            'categories' => $categories
        ]);
    }
}
