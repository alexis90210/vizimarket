<?php

namespace App\Controller;

use stdClass;
use App\Entity\Client;
use App\Entity\Marque;
use DateTimeImmutable;
use App\Entity\Produit;
use App\Entity\Vendeur;
use App\Entity\Attribut;
use App\Entity\Gallerie;
use App\Form\ClientType;
use App\Entity\Attribute;
use App\Entity\Categorie;
use App\Entity\Etiquette;
use App\Form\AttributType;
use App\Form\EditFormType;
use App\Form\AddMarqueType;
use App\Form\AttributeType;
use App\Form\CategorieType;
use App\Form\EtiquetteType;
use App\Entity\SousAttribut;
use App\Entity\ProduitSimple;
use App\Entity\SousCategorie;
use App\Form\ProduitEditType;
use App\Form\AddCategorieType;
use App\Form\AddEtiquetteType;
use App\Form\SousCategorieType;
use App\Entity\CategoriePrincipal;
use App\Entity\Commandes;
use App\Form\CategoriePrincipalType;
use App\Repository\ClientRepository;
use App\Repository\MarqueRepository;
use App\Entity\ConfigurationCreateur;
use App\Repository\ProduitRepository;
use App\Repository\VendeurRepository;
use App\Repository\AttributRepository;
use App\Repository\CreateurRepository;
use App\Repository\CategorieRepository;
use App\Repository\EtiquetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConversationRepository;
use App\Repository\ModePaiementRepository;
use App\Repository\ProduitSimpleRepository;
use App\Entity\ConfigurationPaiementCreateur;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ConfigurationPaiementCreateurType;
use App\Repository\CommandesRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ConfigurationCreateurRepository;
use App\Repository\ConfigurationPaiementCreateurRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('{role}/dashboard', name: 'app_')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(string $role, SessionInterface $session, ClientRepository $client, 
    VendeurRepository $vendeur, CreateurRepository $createur, CommandesRepository $commandes, ProduitSimpleRepository $produits ): Response
    {

        $session->set('user', $this->getUser());
        $session->save();

        return $this->render('dashboard/index.html.twig', [
            'clients' => $client->findAll(),
            'vendeurs' => $vendeur->findAll(),
            'createurs' => $createur->findAll(),
            'commandes' => $commandes->findAll(),
            'produits' => $produits->findAll()
        ]);
    }

    #[Route('/produit/nouveau', name: 'produit_new')]
    public function produit_new(string $role, CategorieRepository $categorieRepository, MarqueRepository $marqueRepository, AttributRepository $attributRepository): Response
    {
        $categories = $categorieRepository->findBy(['parent' => null]);

        $allCategorie = $categorieRepository->findAll();

        $marques = $marqueRepository->findAll();

        $attributsPrincipaux = $attributRepository->findAll();



        return $this->render('dashboard/produit_new/index.html.twig', [
            'categories' => $categories,
            'marques' => $marques,
            'allCategorie' => $allCategorie,
            'attributsPrincipaux' => $attributsPrincipaux
        ]);
    }

    #[Route('/produit/edit/{id}', name: 'produit_edit_page', methods: ['GET'])]
    public function produit_edit_page(string $role, ProduitSimple $produitSimple, CategorieRepository $categorieRepository, MarqueRepository $marqueRepository): Response
    {
        $allCategorie = $categorieRepository->findAll();
        $categories = $categorieRepository->findBy(['parent' => null]);
        $marques = $marqueRepository->findAll();
        return $this->render('dashboard/produit_new/edit.html.twig', [
            'produitSimple' => $produitSimple,
            'allCategorie' => $allCategorie,
            'categories' => $categories,
            'marques' => $marques,
            'role' => $role,
            'categorieChecked' => json_encode($produitSimple->getCategorie())
        ]);
    }

    #[Route('/produit-variable/edit/{id}', name: 'produit_variable_edit_page', methods: ['GET'])]
    public function produit_variable_edit_page(
        string $role,
        ProduitSimple $produitSimple,
        CategorieRepository $categorieRepository,
        MarqueRepository $marqueRepository,
        AttributRepository $attributsPrincipaux
    ): Response {

        $allCategorie = $categorieRepository->findAll();
        $categories = $categorieRepository->findBy(['parent' => null]);
        $marques = $marqueRepository->findAll();


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

        $produit = new stdClass();

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

        //----------------------------------------------

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

        //----------------------------------------------
        // Attributs du produit
        $produit->attributsProduit = $attributsProduit;

        return $this->render('dashboard/produit_new/edit_variable.html.twig', [
            'produitSimple' => $produitSimple,
            'allCategorie' => $allCategorie,
            'categories' => $categories,
            'marques' => $marques,
            'role' => $role,
            'data' => $produit,
            'attributsProduits' => $produit->attributsProduit,
            'marque_id' => $produitSimple->getMarque() ? $produitSimple->getMarque()->getId() : '',
            'attributsPrincipaux' => $attributsPrincipaux->findAll(),
            'categorieChecked' => json_encode($produitSimple->getCategorie()) ?? ''
        ]);
    }


    #[Route('/produit/create', name: 'create_produit_new', methods: ['POST'])]
    public function create_new_produit(string $role, Request $request, EntityManagerInterface $em): Response
    {


        $photo = $request->files->get('final-photo-cover');

        $galleries = $request->files->get('final-gallerie');

        $data = $request->request->all();

        $newProductSimple = new ProduitSimple();

        $em->beginTransaction();

        try {
            //code...
            if (isset($data['producttitre']) and !empty($data['producttitre'])) {

                $newProductSimple->setNom($data['producttitre']);
            }

            if (isset($data['productQte'])) {

                $newProductSimple->setQuantite($data['productQte']);
            }

            if (isset($data['productdesc']) and !empty($data['productdesc'])) {

                $newProductSimple->setDescription($data['productdesc']);
            }

            if (isset($data['produit-type']) and !empty($data['produit-type'])) {

                $newProductSimple->setProduitType($data['produit-type']);
            }

            if (isset($data['caracteristique'])) {

                $newProductSimple->setCaracteristique($data['caracteristique']);
            }


            if (isset($data['marque'])) {

                $marqueChoissie = $em->getRepository(Marque::class)->find($data['marque']);

                $newProductSimple->setMarque($marqueChoissie);
            }



            if (isset($data['tarif-regulier']) and $data['tarif-regulier'] > 0) {

                $newProductSimple->setTarifRegulier($data['tarif-regulier']);
            }

            if (isset($data['tarif-promo']) and ((int) ($data['tarif-promo'])) > 0) {

                $newProductSimple->setTarifPromo($data['tarif-promo']);
            }

            if (isset($data['poids']) and $data['poids'] > 0) {

                $newProductSimple->setPoids($data['poids']);
            }
            if (isset($data['longueur']) and !empty($data['longueur'])) {

                $newProductSimple->setLongueur($data['longueur']);
            }

            if (isset($data['largeur']) and $data['largeur'] > 0) {

                $newProductSimple->setLargeur($data['largeur']);
            }

            if (isset($data['hauteur']) and $data['hauteur'] > 0) {

                $newProductSimple->setHauteur($data['hauteur']);
            }

            if (isset($data['prix-livraison']) and $data['prix-livraison'] > 0) {

                $newProductSimple->setPrixLivraison($data['prix-livraison']);
            }

            if (isset($data['informaton-a-savoir']) and !empty($data['informaton-a-savoir'])) {

                $newProductSimple->setInfosASavoir($data['informaton-a-savoir']);
            }

            if (isset($photo)) {

                $filename = md5(uniqid()) . '.' . $photo->guessExtension();
                $photo->move($this->getParameter('image_produits_directory'), $filename);

                // 1. write the http protocol
                $full_url = "http://";

                // 2. check if your server use HTTPS
                if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                    $full_url = "https://";
                }

                $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/produits/" .  $filename;
                $newProductSimple->setImage($lien);
            }

            foreach ($data as $key => $value) {

                $separer = explode('_', $key);

                if (count($separer) == 2) {
                    if ($value == 'on') {

                        $categorieConcerne = $em->getRepository(Categorie::class)->find($separer[1]);
                        $newProductSimple->addCategorie($categorieConcerne);
                    }
                }
            }

            $newProductSimple->setVendeur($em->getRepository(Vendeur::class)->find($this->getUser()->getId()));

            $em->persist($newProductSimple);

            $em->flush();

            if (count($galleries) > 0) {

                foreach ($galleries as $gallerie) {

                    $newGallerie = new Gallerie();

                    $newGallerie->setProduitSimple($newProductSimple);

                    $filename = md5(uniqid()) . '.' . $gallerie->guessExtension();
                    $gallerie->move($this->getParameter('image_produits_directory'), $filename);

                    // 1. write the http protocol
                    $full_url = "http://";

                    // 2. check if your server use HTTPS
                    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                        $full_url = "https://";
                    }

                    $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/produits/" .  $filename;
                    $newGallerie->setImage($lien);

                    $em->persist($newGallerie);

                    $em->flush();
                }
            }

            $em->commit();
            return $this->redirectToRoute('app_liste_produit', ['role' => $role]);
        } catch (\Throwable $th) {
            //throw $th;
            //$em->rollback();
            dd($th);
        }
    }


    #[Route('/attributs', name: 'attributs')]
    public function attributs(string $role, Request $request, EntityManagerInterface $em): Response
    {

        $attribut = new Attribut();

        $form = $this->createForm(AttributType::class, $attribut);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $type = $form->get('type')->getData();


            $attribut->setType($type);

            $em->persist($attribut);
            $em->flush();

            $attribut = new Attribut();

            $form = $this->createForm(AttributType::class, $attribut);

            $this->addFlash('success', 'L\'attribut a ete ajouter avec success');
        }


        $allAttribute = $em->getRepository(Attribut::class)->findAll();

        return $this->render('dashboard/attributs/index.html.twig', [
            'form' => $form,
            'allAttribute' => $allAttribute
        ]);
    }

    #[Route('/delete/attribut/{id}', name: 'delete_attribut')]
    public function delete_attributs(string $role, Attribut $attribut, EntityManagerInterface $em): Response
    {

        $em->remove($attribut);

        $em->flush();

        return $this->redirectToRoute('app_attributs', ['role' => $role]);
    }

    #[Route('/delete/sous-attribut/{id}', name: 'delete_sous_attributs')]
    public function delete_sous_attributss(string $role, SousAttribut $sousAttribut, EntityManagerInterface $em): Response
    {
        $em->remove($sousAttribut);

        $em->flush();

        return $this->redirectToRoute('app_config_attribut', ['role' => $role, 'id' => $sousAttribut->getAttribut()->getId()]);
    }

    #[Route('/config/attribut/{id}', name: 'config_attribut')]
    public function config_attributs(string $role, Attribut $attribut, EntityManagerInterface $em): Response
    {
        $sousAttributs = $em->getRepository(SousAttribut::class)->findBy(["attribut" => $attribut]);
        return $this->render('dashboard/attributs/config.html.twig', [
            'attribut' => $attribut,
            'sousAttributs' => $sousAttributs
        ]);
    }

    #[Route('/add/sous/attribut/{id}', name: 'sous_attribut', methods: ['POST'])]
    public function add_sous_attribut(string $role, Attribut $attribut, EntityManagerInterface $em, Request $request): Response
    {
        $response = $request->request->all();

        $sousAttribut = new SousAttribut();

        $sousAttribut->setTitre($response['titre']);

        if (isset($response['valeur']) and !empty($response['valeur'])) {

            $sousAttribut->setValeur(
                $response['valeur']
            );
        } else {

            $value = $request->files->get('valeur');

            $filename = md5(uniqid()) . '.' . $value->guessExtension();
            $value->move($this->getParameter('image_attributs_directory'), $filename);

            // 1. write the http protocol
            $full_url = "http://";

            // 2. check if your server use HTTPS
            if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                $full_url = "https://";
            }

            $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/attributs/" .  $filename;

            $sousAttribut->setValeur($lien);
        }



        $sousAttribut->setAttribut($attribut);

        $em->persist($sousAttribut);

        $em->flush();

        return $this->redirectToRoute('app_config_attribut', ['id' => $attribut->getId(), 'role' => $role]);
    }


    #[Route('/edit/attribut/{id}', name: 'edit_attribut', methods: ['POST'])]
    public function edit_attribut(string $role, Request $request, int $id, EntityManagerInterface $em): Response
    {
        // $id = $request->request->get('id');

        $attributeConcerne = $em->getRepository(Attribut::class)->find($id);

        $nom = $request->request->get('nom');

        $attributeConcerne->setNom($nom);

        $type = $request->request->get('type');

        $attributeConcerne->setType($type);

        $em->flush();

        return $this->redirectToRoute('app_attributs', ['role' => $role]);
    }

    #[Route('/edit/etiquette/{id}', name: 'edit_etiquette', methods: ['POST'])]
    public function edit_etiquette(string $role, Request $request, int $id, EntityManagerInterface $em): Response
    {

        $etiquetteConcerne = $em->getRepository(Etiquette::class)->find($id);

        $nom = $request->request->get('edit_nom');

        $etiquetteConcerne->setNom($nom);

        $em->flush();

        return $this->redirectToRoute('etiquettes');
    }

    #[Route('/edit/categorie-principal/{id}', name: 'edit_categorie_principal', methods: ['POST'])]
    public function edit_categorie_principal(string $role, Request $request, int $id, EntityManagerInterface $em): Response
    {

        $categorieConcerne = $em->getRepository(CategoriePrincipal::class)->find($id);

        $nom = $request->request->get('edit_nom');

        $mettreEnAvant = $request->request->get('edit_checkbox');

        if ($mettreEnAvant == 'on') {

            $categorieConcerne->setMettreEnAvant(true);
        } else {
            $categorieConcerne->setMettreEnAvant(false);
        }



        $categorieConcerne->setNom($nom);

        if ($request->files->get('edit_image')) {

            $image = $request->files->get('edit_image');

            $filename = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move($this->getParameter('image_categorie_directory'), $filename);

            // 1. write the http protocol
            $full_url = "http://";

            // 2. check if your server use HTTPS
            if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                $full_url = "https://";
            }

            $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/categories/" .  $filename;
            $categorieConcerne->setImage($lien);
        }

        $em->flush();

        return $this->redirectToRoute('categorie');
    }

    #[Route('/createurs/nouveau', name: 'createurs_nouveau')]
    public function createurs_nouveau(string $role): Response
    {
        return $this->render('dashboard/createurs/_create.html.twig', []);
    }

    #[Route('/createur/profile', name: 'createur_profile')]
    public function createur_profile(string $role): Response
    {

        return $this->render('dashboard/createurs/profile.html.twig', []);
    }

    #[Route('/vendeur/nouveau', name: 'vendeur_nouveau')]
    public function vendeur_nouveau(string $role, CreateurRepository $createur): Response
    {
        return $this->render('dashboard/vendeurs/_create.html.twig', [
            'createurs' => $createur->findAll()
        ]);
    }

    #[Route('/edit/categorie/{id}', name: 'edit_categorie', methods: ['POST'])]
    public function edit_categorie(string $role, Request $request, int $id, EntityManagerInterface $em): Response
    {


        $categorieConcerne = $em->getRepository(Categorie::class)->find($id);

        $nom = $request->request->get('edit_nom');

        $categorieParente = $request->request->get('edit_categorieParent');

        if ($categorieParente == null) {
            $categorieConcerne->setParent(null);
        } else {
            $categorieConcerne->setParent($categorieParente);
        }

        $categorieConcerne->setNom($nom);

        $mettreEnAvant = $request->request->get('edit_checkbox');

        if ($mettreEnAvant == 'on') {

            $categorieConcerne->setEnAvant(true);
        } else {
            $categorieConcerne->setEnAvant(false);
        }


        $image = $request->files->get('edit_image');

        if (!empty($image)) {

            $filename = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move($this->getParameter('image_categorie_directory'), $filename);

            // 1. write the http protocol
            $full_url = "http://";

            // 2. check if your server use HTTPS
            if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                $full_url = "https://";
            }

            $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/categories/" .  $filename;
            $categorieConcerne->setLogo($lien);
        }



        $em->flush();

        return $this->redirectToRoute('app_categorie', ['role' => $role]);
    }

    #[Route('/edit/sous-categorie/{id}', name: 'edit_sous_categorie', methods: ['POST'])]
    public function edit_sous_categorie(string $role, Request $request, int $id, EntityManagerInterface $em): Response
    {

        $sousCategorieConcerne = $em->getRepository(SousCategorie::class)->find($id);

        $nom = $request->request->get('edit_nom');

        $sousCategorieConcerne->setNom($nom);

        $id = $sousCategorieConcerne->getCategorie()->getId();

        $em->flush();

        return $this->redirectToRoute('sous_categorie', ['id' => $id]);
    }

    #[Route('/categories', name: 'categorie')]
    public function categories(string $role, Request $request, EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Categorie::class)->findBy(['parent' => null]);

        // dd($categories);

        $categorieParentes = ['Selectionner categorie parente' => null];

        foreach ($categories as  $categorie) {

            $categorieParentes = array_merge($categorieParentes, [$categorie->getNom() => $categorie->getId()]);
        }

        $categorie = new Categorie();

        $form = $this->createForm(AddCategorieType::class, $categorie, ['categories' => $categorieParentes]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();

            $categorieParent = $form->get('parent')->getData();

            $categorie->setParent($categorieParent);

            $mettreEnAvant = $form->get('en_avant')->getData();

            $categorie->setEnAvant($mettreEnAvant);
            if ($image) {
                $filename = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('image_categorie_directory'), $filename);

                // 1. write the http protocol
                $full_url = "http://";

                // 2. check if your server use HTTPS
                if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                    $full_url = "https://";
                }

                $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/categories/" .  $filename;
                $categorie->setImage($lien);

                $em->persist($categorie);
                $em->flush();

                //--------------------------------------Modification ici------------------------

                $categories = $em->getRepository(Categorie::class)->findBy(['parent' => null]);

                $categorieParentes = ['Selectionner categorie parente' => null];

                foreach ($categories as  $categorie) {

                    $categorieParentes = array_merge($categorieParentes, [$categorie->getNom() => $categorie->getId()]);
                }


                $categorie = new Categorie();
                $form = $this->createForm(AddCategorieType::class, $categorie, ['categories' => $categorieParentes]);

                $this->addFlash('success', 'La categorie a ete ajoutee avec success');
            }
        }

        $allCategorie = $em->getRepository(Categorie::class)->findAll();
        return $this->render('dashboard/categorie/index.html.twig', [
            'form' => $form,
            'allCategorie' => $allCategorie,
        ]);
    }

    #[Route('/delete/categorie/{id}', name: 'delete_categorie')]
    public function delete_categorie(string $role, Categorie $categorie, EntityManagerInterface $em): Response
    {
        $em->remove($categorie);

        $em->flush();

        return $this->redirectToRoute('app_categorie', ['role' => $role]);
    }

    #[Route('/delete/client/{id}', name: 'delete_client')]
    public function delete_client(string $role, Client $client, EntityManagerInterface $em): Response
    {
        $em->remove($client);

        $em->flush();

        return $this->redirectToRoute('clients');
    }

    #[Route('/edit/client/{id}', name: 'edit_client')]
    public function edit_client(string $role, Client $client, EntityManagerInterface $em): Response
    {

        return $this->redirectToRoute('clients');
    }

    #[Route('/clients', name: 'clients')]
    public function clients(string $role, ClientRepository $repo): Response
    {
        $allClient = $repo->findAll();

        return $this->render('dashboard/clients/index.html.twig', [
            'controller_name' => 'ClientsController',
            'allClient' => $allClient
        ]);
    }


    #[Route('/client/nouveau', name: 'addclient')]
    public function addClient(string $role, Request $request, EntityManagerInterface $em): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $photo = $form->get('Photo')->getData();
            if ($photo) {
                $filename = md5(uniqid()) . '.' . $photo->guessExtension();
                $photo->move($this->getParameter('image_clients_directory'), $filename);

                // 1. write the http protocol
                $full_url = "http://";

                // 2. check if your server use HTTPS
                if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                    $full_url = "https://";
                }

                $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/clients/" .  $filename;
                $client->setPhoto($lien);
                $client->setCreatedAt(new DateTimeImmutable());
                $em->persist($client);
                $em->flush();

                $client = new Client();
                $form = $this->createForm(ClientType::class, $client);

                $this->addFlash('success', 'Le client a ete ajoute avec success');

                return $this->redirectToRoute('clients');
            }
        }

        return $this->render('dashboard/clients/addClient.html.twig', [
            'controller_name' => 'ClientsController',
            'form' => $form
        ]);
    }

    #[Route('/commandes', name: 'commandes')]
    public function commandes(string $role, CommandesRepository $commandesRepository): Response
    {
        $commandes = $commandesRepository->findAll();

        return $this->render('dashboard/commandes/index.html.twig', [
            'commandes' => $commandes
        ]);
    }

    #[Route('/details/commandes/{id}', name: 'details_commandes')]
    public function detail_commandes(string $role, Commandes $commande ): Response
    {
        return $this->render('dashboard/commandes/details.html.twig', [
            'commande' => $commande
        ]);
    }

    #[Route('/createurs', name: 'createurs')]
    public function createurs(string $role, CreateurRepository $createur): Response
    {
        return $this->render('dashboard/createurs/index.html.twig', [
            'createurs' => $createur->findAll(),
        ]);
    }

    #[Route('/marques', name: 'marques')]
    public function Marques(string $role, Request $request, EntityManagerInterface $em): Response
    {
        $marque = new Marque();

        $form = $this->createForm(AddMarqueType::class, $marque);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $logo = $form->get('logo')->getData();

            $filename = md5(uniqid()) . '.' . $logo->guessExtension();
            $logo->move($this->getParameter('image_marques_directory'), $filename);

            // 1. write the http protocol
            $full_url = "http://";

            // 2. check if your server use HTTPS
            if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                $full_url = "https://";
            }

            $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/marques/" .  $filename;
            $marque->setLogo($lien);

            $em->persist($marque);
            $em->flush();

            $marque = new Marque();

            $form = $this->createForm(AddMarqueType::class, $marque);

            $this->addFlash('success', 'La marque a ete creer avec success');
        }


        $allMarque = $em->getRepository(Marque::class)->findAll();


        return $this->render('dashboard/marques/index.html.twig', [
            'form' => $form,
            'allMarque' => $allMarque
        ]);
    }


    #[Route('/edit/marque/{id}', name: 'edit_marque')]
    public function edit_marque(string $role, Request $request, EntityManagerInterface $em, Marque $marque): Response
    {
        $logo = $request->files->get('logo');

        if (!empty($logo)) {

            $filename = md5(uniqid()) . '.' . $logo->guessExtension();
            $logo->move($this->getParameter('image_marques_directory'), $filename);

            // 1. write the http protocol
            $full_url = "http://";

            // 2. check if your server use HTTPS
            if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                $full_url = "https://";
            }

            $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/marques/" .  $filename;
            $marque->setLogo($lien);
        }

        $nom = $request->request->get('nom');

        if (!empty($nom)) {

            $marque->setNom($nom);
        }

        $em->flush();

        return $this->redirectToRoute('app_marques', ['role' => $role]);
    }


    #[Route('/delete/marque/{id}', name: 'delete_marque')]
    public function delete_marque(string $role, Marque $marque, EntityManagerInterface $em): Response
    {
        $em->remove($marque);

        $em->flush();

        return $this->redirectToRoute('app_marques', ['role' => $role]);
    }


    #[Route('/produits', name: 'liste_produit')]
    public function produits(string $role, ProduitSimpleRepository $repo): Response
    {
        $produits = $repo->findAll();

        return $this->render('dashboard/liste_produit/index.html.twig', [
            'produits' => array_reverse($produits)
        ]);
    }

    #[Route('/edit/produit/{id}', name: 'edit_produit', requirements: ["id" => "\d+"], methods: ['POST'])]
    public function edit_produit(string $role, Request $request, ProduitSimple $produitSimple, EntityManagerInterface $em): Response
    {

        $photo = $request->files->get('final-photo-cover');

        $galleries = $request->files->get('final-gallerie');

        $data = $request->request->all();

        $em->beginTransaction();

        try {
            //code...
            if (isset($data['producttitre']) and !empty($data['producttitre'])) {

                $produitSimple->setNom($data['producttitre']);
            }

            if (isset($data['productQte'])) {

                $produitSimple->setQuantite($data['productQte']);
            }

            if (isset($data['productdesc']) and !empty($data['productdesc'])) {

                $produitSimple->setDescription($data['productdesc']);
            }

            if (isset($data['produit-type']) and !empty($data['produit-type'])) {

                $produitSimple->setProduitType($data['produit-type']);
            }

            if (isset($data['caracteristique'])) {

                $produitSimple->setCaracteristique($data['caracteristique']);
            }


            if (isset($data['marque'])) {

                $marqueChoissie = $em->getRepository(Marque::class)->find($data['marque']);

                $produitSimple->setMarque($marqueChoissie);
            }

            if (isset($data['tarif-regulier']) and $data['tarif-regulier'] > 0) {

                $produitSimple->setTarifRegulier($data['tarif-regulier']);
            }

            if (isset($data['tarif-promo']) and ((int) ($data['tarif-promo'])) > 0) {

                $produitSimple->setTarifPromo($data['tarif-promo']);
            }

            if (isset($data['poids']) and $data['poids'] > 0) {

                $produitSimple->setPoids($data['poids']);
            }
            if (isset($data['longueur']) and !empty($data['longueur'])) {

                $produitSimple->setLongueur($data['longueur']);
            }

            if (isset($data['largeur']) and $data['largeur'] > 0) {

                $produitSimple->setLargeur($data['largeur']);
            }

            if (isset($data['hauteur']) and $data['hauteur'] > 0) {

                $produitSimple->setHauteur($data['hauteur']);
            }

            if (isset($data['prix-livraison']) and $data['prix-livraison'] > 0) {

                $produitSimple->setPrixLivraison($data['prix-livraison']);
            }

            if (isset($data['informaton-a-savoir']) and !empty($data['informaton-a-savoir'])) {

                $produitSimple->setInfosASavoir($data['informaton-a-savoir']);
            }



            if (isset($photo)) {

                $filename = md5(uniqid()) . '.' . $photo->guessExtension();
                $photo->move($this->getParameter('image_produits_directory'), $filename);

                // 1. write the http protocol
                $full_url = "http://";

                // 2. check if your server use HTTPS
                if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                    $full_url = "https://";
                }

                $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/produits/" .  $filename;
                $produitSimple->setImage($lien);
            }

            foreach ($produitSimple->getCategorie() as $oldCategorie) {

                $produitSimple->removeCategorie($oldCategorie);
            }

            foreach ($data as $key => $value) {

                $separer = explode('_', $key);

                if (count($separer) == 2) {
                    if ($value == 'on') {

                        $categorieConcerne = $em->getRepository(Categorie::class)->find($separer[1]);
                        $produitSimple->addCategorie($categorieConcerne);
                    }
                }
            }

            //$produitSimple->setVendeur($em->getRepository(Vendeur::class)->find($this->getUser()->getId()));


            $em->flush();




            if (count($galleries) > 0) {

                foreach ($galleries as $gallerie) {

                    $newGallerie = new Gallerie();

                    $newGallerie->setProduitSimple($produitSimple);

                    $filename = md5(uniqid()) . '.' . $gallerie->guessExtension();
                    $gallerie->move($this->getParameter('image_produits_directory'), $filename);

                    // 1. write the http protocol
                    $full_url = "http://";

                    // 2. check if your server use HTTPS
                    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                        $full_url = "https://";
                    }

                    $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/produits/" .  $filename;
                    $newGallerie->setImage($lien);

                    $em->persist($newGallerie);

                    $em->flush();
                }
            }

            $em->commit();
            return $this->redirectToRoute('app_liste_produit', ['role' => $role]);
        } catch (\Throwable $th) {
            //throw $th;
            //$em->rollback();
            dd($th);
        }

        return $this->redirectToRoute('app_liste_produit', ['role' => $role]);
    }

    #[Route('/delete/produitSimple/{id}', name: 'delete_produit', requirements: ["id" => "\d+"], methods: ['GET'])]
    public function delete_produit_simple(string $role, ProduitSimple $produit, EntityManagerInterface $em): Response
    {
        $em->remove($produit);

        $em->flush();

        return $this->redirectToRoute('app_liste_produit', ['role' => $role]);
    }

    #[Route('/delete/produitVariable/{id}', name: 'delete_produit_variable', requirements: ["id" => "\d+"], methods: ['GET'])]
    public function delete_produit_variable(string $role, ProduitSimple $produit, EntityManagerInterface $em): Response
    {
        $variations = $produit->getVariations();

        $categories = $produit->getCategorie();

        $galleries = $produit->getGalleries();

        $commentairesProduit = $produit->getCommentaireProduits();

        $termes = $produit->getTerms();

        if (count($variations) > 0) {

            foreach ($variations as $variation) {

                $gallerieVariables = $variation->getGallerieVariables();


                $produit->removeVariation($variation);
                $em->flush();
            }
        }



        if (count($categories) > 0) {
            foreach ($categories as $categorie) {

                $produit->removeCategorie($categorie);

                $em->flush();
            }
        }

        if (count($galleries) > 0) {

            foreach ($galleries as $gallerie) {

                //$em->remove($gallerie);
                $produit->removeGallery($gallerie);

                $em->flush();
            }
        }

        if (count($commentairesProduit) > 0) {

            foreach ($commentairesProduit as $commentaireProduit) {

                //$em->remove($commentaireProduit);

                $produit->removeCommentaireProduit($commentaireProduit);

                $em->flush();
            }
        }


        if (count($termes) > 0) {
            foreach ($termes as $terme) {


                $produit->removeTerm($terme);

                $em->flush();
            }
        }



        $em->remove($produit);

        $em->flush();

        return $this->redirectToRoute('app_liste_produit', ['role' => $role]);
    }


    #[Route('/messagerie', name: 'messagerie')]
    public function messagerie(string $role, ConversationRepository $conversationRepository, EntityManagerInterface $em): Response
    {

        $vendeur = $this->getUser();

        //$vendeur = $em->getRepository(Vendeur::class)->find();
        $allConversation = $conversationRepository->findBy(['vendeur' => $vendeur]);


        return $this->render('dashboard/messagerie/index.html.twig', [
            'controller_name' => 'MessagerieController',
            'conversations' => $allConversation
        ]);
    }

    #[Route('/paiement-modes', name: 'paiement_mode')]
    public function paiement_modes(
        string $role,
        Request $request,
        ConfigurationPaiementCreateurRepository $configurationPaiementCreateurRepository,
        EntityManagerInterface $em,
        ModePaiementRepository $ModePaiementRepository
    ): Response {

        if ($this->getUser()->getAccess() == 'createur') {

            $configuration = new ConfigurationPaiementCreateur();

            $error = '';

            $form = $this->createForm(ConfigurationPaiementCreateurType::class, $configuration)->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $file = $form->get('logo')->getData();


                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('image_paiement_directory'), $filename);

                // 1. write the http protocol
                $full_url = "http://";

                // 2. check if your server use HTTPS
                if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                    $full_url = "https://";
                }

                $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/paiement/" .  $filename;

                $configuration->setIsActif(false);
                $configuration->setLogo($lien);

                try {

                    $em->persist($configuration);
                    $em->flush();
                } catch (\Throwable $e) {
                    $error = $e->getMessage();
                }
            }

            return $this->render('dashboard/paiement_mode/createur.html.twig', [
                'modesPaiements' => $configurationPaiementCreateurRepository->findAll(),
                'form' => $form->createView(),
                'error' => $error
            ]);
        } else  if ($this->getUser()->getAccess() == 'vendeur') {

            dd('working on');


            return $this->render('dashboard/paiement_mode/vendeur.html.twig', [
                'configurationPaiementCreateur' => $configurationPaiementCreateurRepository->findAll(),
                'ModePaiementRepository' => $ModePaiementRepository->findAll()
            ]);
        }
    }

    #[Route('/vendeurs', name: 'vendeurs')]
    public function vendeurs(string $role, VendeurRepository $vendeur): Response
    {
        return $this->render('dashboard/vendeurs/index.html.twig', [
            'vendeurs' => $vendeur->findAll()
        ]);
    }

    #[Route('/configuration/design', name: 'configuration_design')]
    public function configuration_design(string $role, ConfigurationCreateurRepository $ConfigurationCreateurRepository): Response
    {
        $configuration = $ConfigurationCreateurRepository->find($this->getUser()->getId());

        return $this->render('dashboard/configuration/design.html.twig', [
            'configuration' => $configuration ?? new ConfigurationCreateur()
        ]);
    }

    #[Route('/configuration/contenu', name: 'configuration_contenu')]
    public function configuration_contenu(string $role, ConfigurationCreateurRepository $ConfigurationCreateurRepository): Response
    {
        $configuration = $ConfigurationCreateurRepository->find($this->getUser()->getId());

        return $this->render('dashboard/configuration/contenu.html.twig', [
            'configuration' => $configuration ?? new ConfigurationCreateur()
        ]);
    }
}
