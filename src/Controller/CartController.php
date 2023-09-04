<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session, ProduitsRepository $produitsRepository): Response
    {

        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;

        foreach($panier as $id => $quantite){
            $product = $produitsRepository->find($id);
            $dataPanier[] = [
                "produit" => $product,
                "quantite" => $quantite
            ];
            $total += $product->getPrix() * $quantite;
        }

        return $this->render('cart/indexCart.html.twig');
    }

    #[Route('/cart/commande', name: 'app_cart_commande')]
    public function commande(SessionInterface $session, ProduitsRepository $produitsRepository){
        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;

        foreach($panier as $id => $quantite){
            $product = $productsRepository->find($id);
            $dataPanier[] = [
                "produit" => $product,
                "quantite" => $quantite
            ];
            $total += $product->getPrice() * $quantite;
        }

        return $this->render('cart/commande.html.twig', [
            'dataPanier' => $dataPanier,
            'total' => $total
        ]);
    }

    #[Route('/cart_commande_add_{id<\d+>}', name: 'app_cart_commande_add')]
    public function add(Produits $product, SessionInterface $session){
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("app_cart");
    }

    #[Route('/cart_commande_delete_{id<\d+>}', name: 'app_cart_commande_delete')]
    public function delete(Produits $product, SessionInterface $session){
  
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }
}
