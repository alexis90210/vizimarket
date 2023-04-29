<?php

namespace App\Controller;

use DateTime;
use App\Entity\Client;
use App\Entity\ProduitSimple;
use Symfony\Component\Mime\Email;
use App\Entity\CommentaireProduit;
use App\Entity\ReponseCommentaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/commentaire', name: 'app_commentaire')]

class CommentaireController extends AbstractController
{
    #[Route('/add/by/produit/{id}', name: '_add_by_produit', methods: ['POST'])]
    public function add_commentaire_by_produit(Request $request, ProduitSimple $produit, EntityManagerInterface $em, MailerInterface $mailer): Response
    {

        $form = $request->request->all();

        $commentaireProduit = new CommentaireProduit();

        $vendeur = $produit->getVendeur();

        $commentaireProduit->setProduit($produit);

        if (isset($form['client'])) {

            $clientConserne = $em->getRepository(Client::class)->find($form['client']);

            $commentaireProduit->setClient($clientConserne);

            $commentaireProduit->setType(1);
        }

        if (isset($form['vendeur'])) {

            $vendeurConcernee = $em->getRepository(Client::class)->find($form['vendeur']);

            $commentaireProduit->setVendeur($vendeurConcernee);

            $commentaireProduit->setType(2);
        }

        if (isset($form['commentaire'])) {

            $commentaireProduit->setContenu($form['commentaire']);
        }


        $commentaireProduit->setDate(date('d/m/Y H:i:s'));

        $em->persist($commentaireProduit);

        $em->flush();




        if (!empty($form['client'])) {
            try {
                $admin = $this->getParameter('app.admin_mail');
                $full_url = "http://";
                if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                    $full_url = "https://";
                }

                $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/detail-produit/" .  $produit->getId();
                $email = (new Email())
                    ->from($admin)
                    ->to($vendeur->getEmail())
                    ->priority(Email::PRIORITY_HIGH)
                    ->subject("Notification")
                    ->html("
                    Salut {$vendeur->getNom()},
                    <br/>
                    Vous a un nouveau commentaire sur le produit {$produit->getNom()} </b>.
                    <br/>
                    Vous pouvez voir ce commentaire en cliquant sur ce lien <b> { $lien } </b>
                    <br/s>
                    Merci! – L'équipe Vazimarket");

                $sent = $mailer->send($email);
            } catch (\Throwable $th) {

                return $this->json(['code' => 'success', 'message' => $th, 'mail' => 'Mail non transmit']);
            }
        }


        return $this->redirectToRoute('app_product', ['id' => $produit->getId()]);

        //return $this->redirectToRoute();
    }

    #[Route('/add/response/by/commentaire/{id}', name: '_reponse', methods: ['POST'])]
    public function add_commentaire_reponse(Request $request, CommentaireProduit $commentaireProduit, EntityManagerInterface $em): Response
    {
        $reponseCommentaire = new ReponseCommentaire();

        $form = $request->request->all();

        $id = $form['idProduit'];

        if (isset($form['expediteur'])) {

            $expediteur = $em->getRepository(Client::class)->find($form['expediteur']);

            $reponseCommentaire->setExpediteur($expediteur);
        }

        if (isset($form['commentaire'])) {

            $reponseCommentaire->setContenu($form['commentaire']);
        }

        $reponseCommentaire->setCommentaire($commentaireProduit);


        $reponseCommentaire->setDate(date("d/m/Y H:i:s"));


        $em->persist($reponseCommentaire);

        $em->flush();

        return $this->redirectToRoute('app_product', ['id' => $id]);

        //return $this->redirectToRoute();
    }
}