<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\CommentaireProduitRepository;
use App\Repository\ProduitRepository;
use App\Repository\ProduitSimpleRepository;
use App\Repository\VendeurRepository;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/detail-produit/{id}', name: 'app_product')]
    public function index(ProduitSimpleRepository $produitSimple, int $id, CommentaireProduitRepository $commentaireProduitRepository): Response
    {
        $current_produit = $produitSimple->find($id);

        $commentaires = $commentaireProduitRepository->findBy(['produit' => $current_produit]);

        $proprieraire  = $current_produit->getVendeur();

        $categories = $current_produit->getCategorie();

        $categorie = count($categories) > 0 ? $categories[0] : null;

        $similaires =  $categorie ? ($categorie->getProduitSimples() ? $categorie->getProduitSimples() : []) : [];

        $produit = new stdClass();

        if ($current_produit->getProduitType() == 2) {

            //  Load termes
            $Termes = [];
            foreach ($current_produit->getTerms() as $term) {
                $Termes[] = array(
                    'attribut' => $term->getAttribut()->getNom(),
                    'attribut_id' => $term->getAttribut()->getId(),
                    'type' =>  $term->getAttribut()->getType(),
                    'terme' => $term->getTitre(),
                    'terme_id' => $term->getId(),
                    'terme_valeur' => $term->getValeur()
                );
            }

            $attributs = array_unique(array_column($Termes, 'attribut'));
            $attributsProduit = [];
            foreach ($attributs as $attribut) {
                $t = [];
                $type = '';
                $attribut_id = '';
                foreach ($Termes as $terme) {

                    if ($attribut == $terme['attribut']) {
                        $termObj = new stdClass();
                        $termObj->terme = $terme['terme'];
                        $termObj->id = $terme['terme_id'];
                        $termObj->valeur = $terme['terme_valeur'];
                        $attribut_id = $terme['attribut_id'];
                        $type =  $terme['type'];
                        $t[] = $termObj;
                    }
                }

                $tempAttribut = new stdClass();
                $tempAttribut->attribut = $attribut;
                $tempAttribut->id = $attribut_id;
                $tempAttribut->type = $type;
                $tempAttribut->terme = $t;
                $attributsProduit[] = $tempAttribut;
            }

            // Attributs du produit
            $produit->attributsProduit = $attributsProduit;
        }


        // dd($produit->attributsProduit); 

        //Je recupere les variations du produit s'il est variable

        $prix = [];

        $minPrix = 0;
        $maxPrix = 0;

        if ($current_produit->getProduitType() == 2) {

            $variations = $current_produit->getVariations();

            foreach ($variations as $variation) {

                array_push($prix, (int) $variation->getPrix());

                if ($variation->getPrixPromo() !== null and ((int) $variation->getPrixPromo() > 0)) {

                    array_push($prix, (int) $variation->getPrixPromo());
                }
            }

            $minPrix = min($prix);
            $maxPrix = max($prix);
        }



        return $this->render('product/index.html.twig', [
            'produit' => $current_produit,
            'id' => $id,
            'autres_produits' => $proprieraire ? $proprieraire->getProduitSimple() : null,
            'similaires' => $similaires,
            'commentaires' => $commentaires,
            'attributs' => $produit->attributsProduit ?? '',
            'minPrix' => $minPrix,
            'ecart' => (float) $current_produit->getTarifRegulier() != 0 ? intval(100 - ((float) $current_produit->getTarifPromo() / (float) $current_produit->getTarifRegulier()) * 100) : '',
            'ecart_variable' => $maxPrix != 0 ? intval(100 - ((float) $minPrix / (float) $maxPrix) * 100) : '',
        ]);
    }
}
