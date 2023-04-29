<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commandes;
use App\Entity\DetailsCommande;
use App\Entity\ProduitSimple;
use App\Entity\StatutPaiement;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/commande', name: 'app_api_commande')]
class ApiCommandeController extends AbstractController
{
    #[Route('/add', name: 'api_commande_add', methods: ['POST'])]
    public function api_commande_add(Request $request, EntityManagerInterface $em): JsonResponse
    {

        $data = json_decode($request->getContent(), false);

        //return $this->json(['debug' => $data->panier]);

        if (empty($data->panier)) {

            return $this->json([
                'code' => 'erreur', 'message' => 'Il manque le panier'
            ]);
        }

        if (empty($data->client)) {

            return $this->json(['code' => 'erreur', 'message' => 'Il manque l\'id du client ']);
        }

        if (empty($data->prixTotal)) {

            return $this->json(['code' => 'erreur', 'message' => 'Il manque l\'id du client ']);
        }

        $em->beginTransaction();

        try {
            //code...

            $client = $em->getRepository(Client::class)->find($data->client);

            $newCommande = new Commandes();

            $newCommande->setPrix($data->prixTotal);

            $statutPaiement = $em->getRepository(StatutPaiement::class)->find(1);

            $newCommande->setStatutPaiement($statutPaiement);

            $newCommande->setClient($client);

            $newCommande->setDateCommande(date("d/m/Y H:i:s"));

            $date = new DateTime(); // crée une nouvelle instance de la classe DateTime avec la date et l'heure actuelles
            $date->modify('+24 hours'); // ajoute 24 heures à la date actuelle
            $date_format = $date->format('d/m/Y H:i:s'); // formatte la date selon le format souhaité (ici, année-mois-jour heure:minute:seconde)

            $newCommande->setDateLivraison($date_format);



            $newCommande->setAdresseLivraison($data->adresseLivraison);

            $newCommande->setPrixLivraison(4000);

            $em->persist($newCommande);

            $em->flush();


            foreach ($data->panier as $detail) {

                $detailCommande = new DetailsCommande();

                $detailCommande->setCommande($newCommande);

                $produit = $em->getRepository(ProduitSimple::class)->find(intval($detail->produit_id));

                $detailCommande->setProduit($produit);

                $detailCommande->setNomProduit($detail->nom);

                $detailCommande->setPrixUnitaire($detail->prix_promo);

                $detailCommande->setPrixTotal((int)$detail->quantite * $detail->prix_promo);

                $detailCommande->setQuantite($detail->quantite);

                $detailCommande->setVendeurProduit($produit->getVendeur()->getNom());

                $em->persist($detailCommande);

                $em->flush();
            }

            $em->commit();

            return $this->json([
                'message' => 'votre commande a ete passee avec success',
                'code' => 'sucess',
                'idCommande' => $newCommande->getId()
            ]);
        } catch (\Throwable $th) {
            //throw $th;

            $em->rollback();

            return $this->json(['code' => "erreur", 'message' => $th]);
        }
    }
}
