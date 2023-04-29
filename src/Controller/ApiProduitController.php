<?php

namespace App\Controller;

use App\Entity\Attribut;
use App\Entity\Categorie;
use App\Entity\Gallerie;
use App\Entity\GallerieVariable;
use App\Entity\Marque;
use App\Entity\ProduitSimple;
use App\Entity\SousAttribut;
use App\Entity\Variation;
use App\Entity\Vendeur;
use Doctrine\ORM\EntityManagerInterface;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/v1', methods: ['POST', "GET"])]
class ApiProduitController extends AbstractController
{

    #[Route('/add/marque',  methods: ['POST'])]
    public function ajouter_etiquette(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), false);

        if (empty($data->nom))  return $this->json(['code' => 'error', 'message' => 'nom manquant']);

        $nom = $data->nom;

        $marque = new Marque();

        $marque->setNom($nom);

        $em->persist($marque);

        $em->flush();

        $allMarque = $em->getRepository(Marque::class)->findAll();

        $finalMarques = [];

        foreach ($allMarque as $categorie) {

            array_push($finalMarques, [
                'nom' => $categorie->getNom(),
                'id' => $categorie->getId()
            ]);
        }

        return $this->json(['code' => 'success', 'message' => $finalMarques]);
    }


    #[Route('/get/checked/produitSimplecategorie/{id}', name: 'produitSimplecategorie',  methods: ['GET'])]
    public function get_checked_categorie(ProduitSimple $produitSimple, EntityManagerInterface $em): JsonResponse
    {
        $categories = $produitSimple->getCategorie();

        $finalCategorie = [];

        foreach ($categories as $categorie) {

            array_push($finalCategorie, [
                'id' => $categorie->getId(),
                'nom' => $categorie->getNom()
            ]);
        }

        return $this->json(['code' => 'success', 'message' => $finalCategorie]);
    }

    #[Route('/get/Gallerie/produitSimplecategorie/{id}', name: 'produitSimplegallerie_recup',  methods: ['GET'])]
    public function get_gallerie_categorie(ProduitSimple $produitSimple,): JsonResponse
    {
        $galleries = $produitSimple->getGalleries();

        $finalGalleries = [];

        foreach ($galleries as $categorie) {

            array_push($finalGalleries, [
                'id' => $categorie->getId(),
                'image' => $categorie->getImage(),
            ]);
        }

        return $this->json(['code' => 'success', 'message' => $finalGalleries]);
    }

    #[Route('/get/sous/attribut/by/{titre}', name: 'sous_attribut_api',  methods: ['POST'])]
    public function get_sous_attribut(Request $request, string $titre, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), false);

        $nom = $titre;

        $attributPrincipal = $em->getRepository(Attribut::class)->findOneBy(['nom' => $nom]);

        $sousAttributs = $attributPrincipal->getSousAttributs();

        $finalSousAttributs = [];

        foreach ($sousAttributs as $sousAttribut) {

            array_push($finalSousAttributs, [
                'titre' => $sousAttribut->getTitre(),
                'valeur' => $sousAttribut->getValeur(),
                'type' => $sousAttribut->getAttribut()->getType()
            ]);
        }

        return $this->json(['code' => 'success', 'message' => $finalSousAttributs]);
    }

    #[Route('/remove/{parent}/Gallerie/{id}', name: 'remove_gallerie_for_product',  methods: ['GET'])]
    public function remove_gallerie(int $id, int $parent, EntityManagerInterface $em): JsonResponse
    {

        $gallerie = $em->getRepository(Gallerie::class)->find($id);

        $em->remove($gallerie);

        $em->flush();



        $galleries = $em->getRepository(Gallerie::class)->findby(['produit_simple' => $parent]);

        $finalGalleries = [];

        foreach ($galleries as $gallerie) {

            array_push($finalGalleries, [
                'id' => $gallerie->getId(),
                'image' => $gallerie->getImage(),
            ]);
        }

        return $this->json(['code' => 'success', 'message' => $finalGalleries]);
    }

    #[Route('/retrieve/produit-variable/{id}', name: 'retrieve_produit_variable',  methods: ['GET'])]
    public function retrieve_produit_variable(ProduitSimple $produitSimple): JsonResponse
    {

        $produit = new stdClass();

        $produit->produitTitre = $produitSimple->getNom();
        $produit->produitQuantite = $produitSimple->getQuantite();
        $produit->produitDescription = $produitSimple->getDescription();
        $produit->produitCaracteristique = $produitSimple->getCaracteristique();
        $produit->produitType = $produitSimple->getProduitType();
        $produit->imageCouverture = $produitSimple->getImage();


        $principalGallerie = [];

        foreach ($produitSimple->getGalleries() as $g) {
            $principalGallerie[] = [
                'image' => $g->getImage(),
                'gallerie_id' => $g->getId()
            ];
        }
        $produit->gallerie = $principalGallerie;

        //  Load termes
        $Termes = [];
        foreach ($produitSimple->getTerms() as $term) {
            $Termes[] = array(
                'attribut' => $term->getAttribut()->getNom(),
                'attribut_id' => $term->getAttribut()->getId(),
                'terme' => $term->getTitre(),
                'terme_id' => $term->getId()
            );
        }

        $attributs = array_unique(array_column($Termes, 'attribut'));
        $attributsProduit = [];
        foreach ($attributs as $attribut) {
            $t = [];
            foreach ($Termes as $terme) {

                if ($attribut == $terme['attribut']) {
                    $termObj = new stdClass();
                    $termObj->terme = $terme['terme'];
                    $termObj->id = $terme['terme_id'];
                    $t[] = $termObj;
                }
            }

            $tempAttribut = new stdClass();
            $tempAttribut->attribut = $attribut;
            $tempAttribut->terme = $t;
            $attributsProduit[] = $tempAttribut;
        }
        $produit->attributsProduit = $attributsProduit;

        //  Load variations
        $variationsList = [];

        foreach ($produitSimple->getVariations() as $var) {
            $variation = new stdClass();
            $variation->imageCouverture = $var->getImageCouverture();

            // variation gallerie
            $gallerieV = [];
            foreach ($var->getGallerieVariables() as $gallerie) {
                $gallerieV[] = array(
                    'image' => $gallerie->getImage(),
                    'gallerie_id' => $gallerie->getId()
                );
            }
            $variation->ImageGalleries = $gallerieV;
            $variation->prixRegulier = $var->getPrix();
            $variation->prixPromo = $var->getPrixPromo();
            $variation->poids = $var->getPoids();
            $variation->Longueur = $var->getLongueur();
            $variation->Largeur = $var->getLargeur();
            $variation->Hauteur = $var->getHauteur();
            $variation->PrixLivraison = $var->getPrixLivraison();
            $variation->InformationLivraisonAsavoir = $var->getInformationASavoir();
            $variation->combinaison = $var->getCombinaison();

            $variationsList[] = $variation;
        }

        $produit->variations = $variationsList;
        $produit->vendeur = $produitSimple->getVendeur()->getId();
        $produit->dateCreation = $produitSimple->getDateCreation();

        return $this->json($produit);
    }


    #[Route('/produit/variable/create', name: 'produit_variable_create',  methods: ['POST'])]
    public function produit_variable_create(EntityManagerInterface $em, Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), false);

        //return $this->json(['data' => $data]);

        if (!isset($data->produitTitre) or empty($data->produitTitre)) {

            return $this->json([
                'code' => 'erreur',
                'message' => 'il manque le titre du produit'
            ]);
        }

        if (!isset($data->produitQuantite) or empty($data->produitQuantite)) {

            return $this->json([
                'code' => 'erreur',
                'message' => 'il manque la quantite'
            ]);
        }

        if (!isset($data->produitDescription) or empty($data->produitDescription)) {

            return $this->json([
                'code' => 'erreur',
                'message' => 'il manque la description'
            ]);
        }


        if (!isset($data->produitCaracteristique) or empty($data->produitCaracteristique)) {

            return $this->json([
                'code' => 'erreur',
                'message' => 'il manque la carcteristique'
            ]);
        }

        if (!isset($data->produitType) or empty($data->produitType)) {

            return $this->json([
                'code' => 'erreur',
                'message' => 'il manque le type du produit'
            ]);
        }

        if (!isset($data->categories) or empty($data->categories)) {

            return $this->json([
                'code' => 'erreur',
                'message' => 'il manque au moins une categorie'
            ]);
        }


        if (!isset($data->imageCouverture) or empty($data->imageCouverture)) {

            return $this->json([
                'code' => 'erreur',
                'message' => 'il l\'image de couverture '
            ]);
        }

        if (!isset($data->vendeur_id) or empty($data->vendeur_id)) {

            return $this->json([
                'code' => 'erreur',
                'message' => 'il manque l\'id du vendeur'
            ]);
        }

        if (!isset($data->imageCouverture) or empty($data->imageCouverture)) {

            return $this->json([
                'code' => 'erreur',
                'message' => 'il manque l\'image de couverture'
            ]);
        }

        $em->beginTransaction();

        try {
            //code...

            //Insertion du produit variable

            $newProduitVariable = new ProduitSimple();

            $newProduitVariable->setNom($data->produitTitre);

            $newProduitVariable->setQuantite($data->produitQuantite);

            $newProduitVariable->setDescription($data->produitDescription);

            $newProduitVariable->setCaracteristique($data->produitCaracteristique);

            $vendeur = $em->getRepository(Vendeur::class)->find($data->vendeur_id);

            $newProduitVariable->setVendeur($vendeur);

            $newProduitVariable->setImage($data->imageCouverture);

            $newProduitVariable->setProduitType($data->produitType);

            if (isset($data->marque) and ($data->marque > 0)) {

                $marque = $em->getRepository(Marque::class)->find($data->marque);

                $newProduitVariable->setMarque($marque);
            }


            //insertion des categorie


            foreach ($data->categories as $categorie) {

                if ($categorie->checked) {

                    $categorieConcernee = $em->getRepository(Categorie::class)->find($categorie->categorie_id);

                    $newProduitVariable->addCategorie($categorieConcernee);
                }
            }
            //insertion des termes

            foreach ($data->attributsProduit as $attributProduit) {

                foreach ($attributProduit->termes as $terme) {

                    $termeChoisie = $em->getRepository(SousAttribut::class)->findOneBy(['valeur' => $terme]);

                    $newProduitVariable->addTerm($termeChoisie);
                }
            }



            //--------------image

            $source = fopen($data->imageCouverture, 'r');
            $nomUnique = md5(uniqid());
            $destination = fopen($this->getParameter('image_produits_directory') . '/' . $nomUnique . '.png', 'w');
            stream_copy_to_stream($source, $destination);
            fclose($source);
            fclose($destination);
            // 1. write the http protocol
            $full_url = "http://";
            // 2. check if your server use HTTPS
            if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                $full_url = "https://";
            }
            $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/produits/" . $nomUnique . '.png';

            $newProduitVariable->setImage($lien);


            $em->persist($newProduitVariable);

            $em->flush();



            //Insertion des gallerie par defaut

            if (isset($data->ImageGalleries)) {

                $galleries = $data->ImageGalleries;

                if (count($galleries) > 0) {

                    foreach ($galleries as $gallerie) {

                        $newGallerie = new Gallerie();

                        $newGallerie->setProduitSimple($newProduitVariable);

                        $source = fopen($gallerie, 'r');
                        $nomUnique = md5(uniqid());
                        $destination = fopen($this->getParameter('image_variables_directory') . '/' . $nomUnique . '.png', 'w');
                        stream_copy_to_stream($source, $destination);
                        fclose($source);
                        fclose($destination);
                        // 1. write the http protocol
                        $full_url = "http://";
                        // 2. check if your server use HTTPS
                        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                            $full_url = "https://";
                        }
                        $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/variables/" . $nomUnique . '.png';

                        $newGallerie->setImage($lien);

                        $em->persist($newGallerie);

                        $em->flush();
                    }
                }
            }


            // return $this->json(['variation' => $data]);
            foreach ($data->variations  as $key => $variation) {

                //return $this->json(['variation' => $key]);


                //return $this->json(['test' => $variation]);
                $newVariation = new Variation();

                $source = fopen($variation->imageCouverture, 'r');
                $nomUnique = md5(uniqid());
                $destination = fopen($this->getParameter('image_variables_directory') . '/' . $nomUnique . '.png', 'w');
                stream_copy_to_stream($source, $destination);
                fclose($source);
                fclose($destination);
                // 1. write the http protocol
                $full_url = "http://";
                // 2. check if your server use HTTPS
                if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                    $full_url = "https://";
                }
                $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/variables/" . $nomUnique . '.png';

                $newVariation->setImageCouverture($lien);

                if (isset($variation->poids) and !empty($variation->poids)) {

                    $newVariation->setPoids($variation->poids);
                }

                if (isset($variation->Hauteur) and !empty($variation->Hauteur)) {

                    $newVariation->setHauteur($variation->Hauteur);
                }

                if (isset($variation->Longueur) and !empty($variation->Longueur)) {

                    $newVariation->setLongueur($variation->Longueur);
                }

                if (isset($variation->Largeur) and !empty($variation->Largeur)) {

                    $newVariation->setLargeur($variation->Largeur);
                }

                if (isset($variation->PrixLivraison) and !empty($variation->PrixLivraison)) {

                    $newVariation->setPrixLivraison($variation->PrixLivraison);
                }

                if (isset($variation->prixRegulier) and !empty($variation->prixRegulier)) {

                    $newVariation->setPrix($variation->prixRegulier);
                }

                if (isset($variation->prixPromo) and !empty($variation->prixPromo)) {

                    $newVariation->setPrixPromo($variation->prixPromo);
                }

                if (isset($variation->InformationLivraisonAsavoir) and !empty($variation->InformationLivraisonAsavoir)) {

                    $newVariation->setInformationASavoir($variation->InformationLivraisonAsavoir);
                }

                $newVariation->setCombinaison($variation->combinaison);

                $newVariation->setProduit($newProduitVariable);

                $em->persist($newVariation);

                $em->flush();


                //Insertion des images dans la gallerie variable 

                if (isset($variation->ImageGalleries) and count($variation->ImageGalleries) > 0) {

                    foreach ($variation->ImageGalleries as $imageGallerie) {

                        $gallerie = new GallerieVariable();

                        $gallerie->setVariation($newVariation);

                        $source = fopen($imageGallerie, 'r');

                        $nomUnique = md5(uniqid());
                        $destination = fopen($this->getParameter('image_variables_directory') . '/' . $nomUnique . '.png', 'w');
                        stream_copy_to_stream($source, $destination);
                        fclose($source);
                        fclose($destination);
                        // 1. write the http protocol
                        $full_url = "http://";
                        // 2. check if your server use HTTPS
                        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                            $full_url = "https://";
                        }
                        $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/variables/" . $nomUnique . '.png';

                        $gallerie->setImage($lien);

                        $em->persist($gallerie);

                        $em->flush();
                    }
                }

                //Insertion des categories

            }
            $em->commit();
            return $this->json(['code' => 'success', 'message' => 'Votre produit a bien ete ajoute avec success']);
        } catch (\Throwable $th) {
            //throw $th;

            $em->rollback();

            return $this->json([
                'code' => 'erreur',
                'message' => $th
            ]);
        }
    }

    #[Route('/produit/variable/edit', name: 'produit_variable_edit',  methods: ['POST'])]
    public function produit_variable_edit(EntityManagerInterface $em, Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), false);

        if (isset($data->idProduit) and !empty($data->idProduit)) {

            return $this->json([
                'code' => 'erreur',
                'message' => 'il manque l\'id du produit'
            ]);
        }

        $em->beginTransaction();

        try {
            //code...

            $produitConcerne = $em->getRepository(ProduitSimple::class)->find($data->idProduit);

            if (isset($data->produitTitre) and !empty($data->produitTitre)) {

                $produitConcerne->setTitre($data->produitTitre);
            }

            if (isset($data->produitQuantite) and !empty($data->produitQuantite)) {

                $produitConcerne->setQuantite($data->produitQuantite);
            }

            if (isset($data->produitDescription) and !empty($data->produitDescription)) {

                $produitConcerne->setDescription($data->produitDescription);
            }

            if (isset($data->produitCaracteristique) and !empty($data->produitCaracteristique)) {

                $produitConcerne->setCaracterisque($data->produitCaracteristique);
            }

            if (isset($data->imageCouverture) and !empty($data->imageCouverture)) {

                $source = fopen($data->imageCouverture, 'r');
                $nomUnique = md5(uniqid());
                $destination = fopen($this->getParameter('image_produits_directory') . '/' . $nomUnique . '.png', 'w');
                stream_copy_to_stream($source, $destination);
                fclose($source);
                fclose($destination);
                // 1. write the http protocol
                $full_url = "http://";
                // 2. check if your server use HTTPS
                if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                    $full_url = "https://";
                }
                $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/produits/" . $nomUnique . '.png';

                $produitConcerne->setImage($lien);
            }

            if (isset($data->marque) and empty($data->marque > 0)) {

                $marque = $em->getRepository(Marque::class)->find($data->marque);

                $produitConcerne->setMarque($marque);
            }

            if (isset($data->categories) and !empty($data->categories)) {

                $categoriesProduit = $produitConcerne->getCategories();

                foreach ($categoriesProduit as $categorieASupprimer) {

                    $produitConcerne->removeCategorie($categorieASupprimer);
                }

                foreach ($data->categories as $categorie) {

                    if ($categorie->checked) {

                        $categorieConcernee = $em->getRepository(Categorie::class)->find($categorie->categorie_id);

                        $produitConcerne->addCategorie($categorieConcernee);
                    }
                }
            }


            //insertion des termes

            if (isset($data->attributsProduit) and !empty($data->attributsProduit)) {

                $termes = $produitConcerne->getTermes();

                foreach ($termes as $terme) {

                    $produitConcerne->removeTerm($terme);

                    $em->flush();
                }

                foreach ($data->attributsProduit as $attributProduit) {

                    foreach ($attributProduit->termes as $terme) {

                        $termeChoisie = $em->getRepository(SousAttribut::class)->findOneBy(['valeur' => $terme]);

                        $produitConcerne->addTerm($termeChoisie);
                    }
                }
            }

            if (isset($data->variations) and !empty($data->variations)) {

                $variations = $produitConcerne->getVariation();

                foreach ($variations as $variation) {

                    $produitConcerne->removeVariation($variation);
                    $em->flush();
                }

                foreach ($data->variations  as $key => $variation) {

                    $newVariation = new Variation();

                    $source = fopen($variation->imageCouverture, 'r');
                    $nomUnique = md5(uniqid());
                    $destination = fopen($this->getParameter('image_variables_directory') . '/' . $nomUnique . '.png', 'w');
                    stream_copy_to_stream($source, $destination);
                    fclose($source);
                    fclose($destination);
                    // 1. write the http protocol
                    $full_url = "http://";
                    // 2. check if your server use HTTPS
                    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                        $full_url = "https://";
                    }
                    $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/variables/" . $nomUnique . '.png';

                    $newVariation->setImageCouverture($lien);

                    if (isset($variation->poids) and !empty($variation->poids)) {

                        $newVariation->setPoids($variation->poids);
                    }

                    if (isset($variation->Hauteur) and !empty($variation->Hauteur)) {

                        $newVariation->setHauteur($variation->Hauteur);
                    }

                    if (isset($variation->Longueur) and !empty($variation->Longueur)) {

                        $newVariation->setLongueur($variation->Longueur);
                    }

                    if (isset($variation->Largeur) and !empty($variation->Largeur)) {

                        $newVariation->setLargeur($variation->Largeur);
                    }

                    if (isset($variation->PrixLivraison) and !empty($variation->PrixLivraison)) {

                        $newVariation->setPrixLivraison($variation->PrixLivraison);
                    }

                    if (isset($variation->prixRegulier) and !empty($variation->prixRegulier)) {

                        $newVariation->setPrix($variation->prixRegulier);
                    }

                    if (isset($variation->prixPromo) and !empty($variation->prixPromo)) {

                        $newVariation->setPrixPromo($variation->prixPromo);
                    }

                    if (isset($variation->InformationLivraisonAsavoir) and !empty($variation->InformationLivraisonAsavoir)) {

                        $newVariation->setInformationASavoir($variation->InformationLivraisonAsavoir);
                    }

                    $newVariation->setCombinaison($variation->combinaison);

                    $newVariation->setProduit($produitConcerne);

                    $em->persist($newVariation);


                    if (isset($variation->ImageGalleries) and count($variation->ImageGalleries) > 0) {

                        foreach ($variation->ImageGalleries as $imageGallerie) {

                            $gallerie = new GallerieVariable();

                            $gallerie->setVariation($newVariation);

                            $source = fopen($imageGallerie, 'r');

                            $nomUnique = md5(uniqid());
                            $destination = fopen($this->getParameter('image_variables_directory') . '/' . $nomUnique . '.png', 'w');
                            stream_copy_to_stream($source, $destination);
                            fclose($source);
                            fclose($destination);
                            // 1. write the http protocol
                            $full_url = "http://";
                            // 2. check if your server use HTTPS
                            if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                                $full_url = "https://";
                            }
                            $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/variables/" . $nomUnique . '.png';

                            $gallerie->setImage($lien);

                            $em->persist($gallerie);

                            $em->flush();
                        }
                    }
                }
            }


            if (isset($data->ImageGalleries) and !empty($data->ImageGalleries)) {

                $galleries = $data->ImageGalleries;

                if (count($galleries) > 0) {

                    foreach ($galleries as $gallerie) {

                        $newGallerie = new Gallerie();

                        $newGallerie->setProduitSimple($produitConcerne);

                        $source = fopen($gallerie, 'r');
                        $nomUnique = md5(uniqid());
                        $destination = fopen($this->getParameter('image_variables_directory') . '/' . $nomUnique . '.png', 'w');
                        stream_copy_to_stream($source, $destination);
                        fclose($source);
                        fclose($destination);
                        // 1. write the http protocol
                        $full_url = "http://";
                        // 2. check if your server use HTTPS
                        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                            $full_url = "https://";
                        }
                        $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/variables/" . $nomUnique . '.png';

                        $newGallerie->setImage($lien);

                        $em->persist($newGallerie);

                        $em->flush();
                    }
                }
            }
        } catch (\Throwable $th) {
            //throw $th;


            return $this->json(['code' => 'erreur', 'message' => $th]);
        }
    }

    #[Route('/recup/variation', name: 'recup_variation',  methods: ['POST'])]
    public function recup_variation(EntityManagerInterface $em, Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), false);

        $idProduit = $data->idProduit;

        $termesRecu = $data->terme;

        $produitConcerne = $em->getRepository(ProduitSimple::class)->find($idProduit);

        $variations = $produitConcerne->getVariations();

        $result = new stdClass();

        foreach ($variations as $variation) {

            $combinaisons = $variation->getCombinaison();

            $n_terme_recu = [];
            foreach ($termesRecu as $terme) {
                $n_terme_recu[] = [
                    'terme' => $terme->terme,
                    'attribut' => $terme->attribut
                ];
            }

            $n_combinaisons = [];
            foreach ($combinaisons as $terme) {

                $n_combinaisons[] = [
                    'terme' => $terme['terme'],
                    'attribut' => $terme['attribut']
                ];
            }

            $taille_max = count($n_combinaisons) == count($n_terme_recu) ?  count($n_combinaisons) : -1;

            $count = 0;

            for ($i = 0; $i < $taille_max; $i++) {
                if (in_array($n_combinaisons[$i], $n_terme_recu)) $count++;
            }

            $isIdentique =  $count == $taille_max;

            if ($isIdentique) {

                $result->imageCouverture = $variation->getImageCouverture();

                $galleries = [];

                foreach ($variation->getGallerieVariables() as $collection) {
                    $galleries[] = array(
                        'image' => $collection->getImage()
                    );
                }
                $result->galleries = $galleries;
                $result->prix = $variation->getPrix();
                $result->prixPromo = $variation->getPrixPromo();
            }
        }

        return $this->json($result);
    }
}
