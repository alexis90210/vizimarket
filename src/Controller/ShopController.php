<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\MarqueRepository;
use App\Repository\ProduitSimpleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    #[Route('/produit/categorie-{id}', name: 'app_produit_categorie')]
    public function app_produit_categorie(MarqueRepository $marque, int $id, CategorieRepository $categorieRepository, EntityManagerInterface $em): Response
    {
        $sousCategories = $categorieRepository->findBy(['parent' => $id]);

        $categorieConcernee = $em->getRepository(Categorie::class)->find($id);

        $produits = $categorieConcernee->getProduitSimples();
        return $this->render('shop/index.html.twig', [
            'marques' => $marque->findAll(),
            'categories' => $sousCategories,
            'produits' => $produits
        ]);
    }

    #[Route('/produit/sous-categorie-{id}/', name: 'app_produit_scategorie')]
    public function app_produit_scategorie(MarqueRepository $marque, int $id, CategorieRepository $categorieRepository, EntityManagerInterface $em): Response
    {
        $sousCategories = $categorieRepository->findBy(['parent' => $id]);

        $categorieConcernee = $em->getRepository(Categorie::class)->find($id);

        $produits = $categorieConcernee->getProduitSimples();

        return $this->render('shop/sous_categorie.html.twig', [
            // 'marques' => $marque->findAll(),
            // 'categories' => $sousCategories,
            'produits' => $produits
        ]);
    }

    #[Route('/boutique/reference-{id}', name: 'app_boutique')]
    public function app_boutique(MarqueRepository $marque, int $id, CategorieRepository $categorieRepository, EntityManagerInterface $em): Response
    {
        $sousCategories = $categorieRepository->findBy(['parent' => $id]);

        $categorieConcernee = $em->getRepository(Categorie::class)->find($id);

        $produits = $categorieConcernee->getProduitSimples();
        return $this->render('shop/index.html.twig', [
            'marques' => $marque->findAll(),
            'categories' => $sousCategories,
            'produits' => $produits
        ]);
    }
}