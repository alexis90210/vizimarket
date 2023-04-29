<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiCategorieController extends AbstractController
{
    #[Route('/api/categories', name: 'api_all_categorie', methods: ['GET'])]
    public function getAllCategorie(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();

        $allCategorie = [];



        foreach ($categories as $categorie) {

            $sousCategorieArray = [];
            foreach ($categories as $sousCategorie) {

                if ($sousCategorie->getParent() == $categorie->getId()) {

                    array_push($sousCategorieArray, ['nom' => $sousCategorie->getNom(), 'id' => $sousCategorie->getId(), 'uuid' => uniqid()]);
                }
            }

            array_push($allCategorie, [
                'id' => $categorie->getId(),
                'nom' => $categorie->getNom(),
                'sousCategorie' => $sousCategorieArray
            ]);
        }

        return $this->json([
            'code' => 'success',
            'message' => $allCategorie
        ]);
    }
}
