<?php

namespace App\Controller;

use App\Entity\Vendeur;
use App\Repository\CategorieRepository;
use App\Repository\VendeurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoreListController extends AbstractController
{
    #[Route('/liste/boutiques', name: 'app_tous_les_vendeurs')]
    public function index(VendeurRepository $vendeur): Response
    {
        return $this->render('store_list/index.html.twig', [
            'vendeurs' => $vendeur->findAll()
        ]);
    }
    #[Route('/detail/boutique/{id}', name: 'app_detail_boutique', methods: ['GET'])]
    public function detail_boutique(Vendeur $vendeur, CategorieRepository $categorieRepository): Response
    {
        $allProduit = $vendeur->getProduitSimple();

        $allCategorie = $categorieRepository->findAll();

        $categories = [];

        foreach ($allProduit as $produit) {

            $categoriesConcernee = $produit->getCategorie();

            if (count($categoriesConcernee) > 0) {

                foreach ($categoriesConcernee as $categorieConcernee) {

                    if (!in_array($categorieConcernee, $categories)) {

                        array_push($categories, ['nom' => $categorieConcernee->getNom(), 'id' => $categorieConcernee->getId()]);
                    }
                }
            }
        }


        return $this->render('les_vendeurs/index.html.twig', [
            'vendeurInfos' => $vendeur,
            'produits' => $allProduit,
            'categories' => $categories,
            'allCategorie' => $allCategorie
        ]);
    }
}
