<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NouveauController extends AbstractController
{
    #[Route('/nouveau', name: 'app_nouveau_home')]
    public function index(CategorieRepository $categorieRepositorie): Response
    {
        $categories = $categorieRepositorie->findAll();

        $categoriesEnAvant = $categorieRepositorie->findBy([
            'en_avant' => true,
        ]);

        // ------------------------------------------
        // ICI ON FAIT LE TRAITEMENT POUR AFFICHER SUR LES TROIS PARTIES DE L'ACCEUIL
        //-------------------------------------------

        return $this->render('nouveau/index.html.twig', [
            'categorie' => $categories
        ]);
    }
}
