<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoriesController extends AbstractController
{
    #[Route('/add_categories', name: 'app_categories_add')]
    public function add(CategoriesRepository $repo, Request $request, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CategoriesType::class);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
            $categories = $form->get('categories')->getData();
            $categorieTab  = explode(',',$categories);
          

            foreach($categorieTab as $valeur){
                $categorie = new Categories();
                $categorie->setNom($valeur);
                $slug = $slugger->slug($categorie->getNom());
                $categorie->setSlug($slug);
        
                $repo->save($categorie);
            }

            $repo->flush();
         
            return $this->redirectToRoute('app_accueil');
         }

        return $this->render('categories/formCategorie.html.twig', [
            "formCat" => $form->createView()
        ]);
    }
}
