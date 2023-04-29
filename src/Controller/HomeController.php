<?php

namespace App\Controller;

use App\Repository\CategoriePrincipalRepository;
use App\Repository\CategorieRepository;
use App\Repository\MarqueRepository;
use App\Repository\ProduitSimpleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CategorieRepository $categorie, ProduitSimpleRepository $produit, MarqueRepository $marque): Response
    {

        $all = $categorie->findBy(['parent' => null]);

        $CategorieMiseEnAvant = [];
        foreach ($all as $principal) {

            if ($principal->isEnAvant()) {
                $filles = $categorie->findBy([
                    'parent' => $principal->getId()
                ]);  

                $arr_filles = [];

                foreach ($filles as $key => $fille) {
                    array_push($arr_filles, [
                        'categorie_id' =>$fille->getId(),
                        'nom' => $fille->getNom()
                    ]);
                }


                $t = [
                    'categorie_id' => $principal->getId(),
                    'nom' => $principal->getNom()
                ];
                $t = array_merge($t, [
                    'filles' => $arr_filles ]) ;
                $t = array_merge($t, [
                        'produits' => $principal->getProduitSImples() ]) ;
                array_push($CategorieMiseEnAvant, $t);             
            }

        }

        return $this->render('home/index.html.twig', [
            'allCategoriePrincipal' => $all,
            'en_avant' => $CategorieMiseEnAvant,
            'produits' => $produit->findAll(),
            'marques' => $marque->findAll()
        ]);
    }
}
