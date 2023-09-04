<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitsType;
use App\Repository\ProduitsRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitsController extends AbstractController
{
    #[Route('/produits_add', name: 'app_produits_add')]
    public function addProduits(Request $request,ProduitsRepository $repo,SluggerInterface $slugger): Response
    {
        $produit = new Produits();
        $form = $this->createForm(ProduitsType::class,$produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('photoForm')->getData();
            $filename = $slugger->slug($produit->getTitre()) . uniqid() . '.'. $file->guessExtension();
            try {
                $file->move($this->getParameter('produit_photos'),$filename);
            } catch (FileException $e) {
                //throw $th;
            }
            $produit->setPhoto($filename);
            $repo->save($produit,1);
            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('produits/ficheProduits.html.twig', [
            'formProduit' => $form->createView(),
        ]);
    }

    #[Route('/produits', name: 'app_produits')]
    public function allProducts(ProduitsRepository $repo, CategoriesRepository $repoCat){
        $produits = $repo->findAll();
        $categories = $repoCat->findAll();

        return $this->render('produits/allProduit.html.twig', [
            'produits' => $produits,
            'categories' => $categories
        ]);
    }

    #[Route('/produits/categorie/{slug}', name: 'app_produits_categorie')]
    public function productsByCategoirie(CategoriesRepository $repo,$slug){
        $categorie = $repo->findOneBy(['slug'=>$slug]);
        $produits = $categorie->getProduits();
        $categories=$repo->findAll();
        return $this->render('produits/allProduit.html.twig', [
            'produits' => $produits,
            'categories' => $categories,
            'categorie' => $categorie
        ]);
    }

    #[Route('/produits_{id<\d+>}', name: 'app_produits_fiche')]
    public function oneProduit($id, ProduitsRepository $repo){
        $produit = $repo->find($id);

        return $this->render('produits/ficheProduit.html.twig', [
            'produit' => $produit,
            
        ]);
    }

    #[Route('/update_produit_{id<\d+>}', name: 'app_produit_update')]
    public function update($id, Request $request, ProduitRepository $repo){
        $produit = $repo->find($id);

        $form = $this->createForm(ProduitType::class,$produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('photoForm')->getData();
            if($file){

            
            $filename = $slugger->slug($produit->getTitre()) . uniqid() . '.'. $file->guessExtension();
            try {
                $file->move($this->getParameter('produit_photos'),$filename);
            } catch (FileException $e) {
                //throw $th;
            }
            $produit->setPhoto($filename);

            }
            $repo->save($produit,1);
            return $this->redirectToRoute('app_produits');
        }

        return $this->render('produit/ficheProduits.html.twig', [
            'formProduit' => $form->createView(),
        ]);
    }

    #[Route('/delete_produit_{id<\d+>}', name: 'app_produit_delete')]
    public function delete($id, ProduitRepository $repo){
        $produit = $repo->find($id);
        $repo->remove($produit,1);
        return $this->redirectToRoute('app_produits');
    }

}
