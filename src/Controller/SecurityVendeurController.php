<?php

namespace App\Controller;

use App\Entity\Vendeur;
use App\Form\VendeurType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityVendeurController extends AbstractController
{
    #[Route(path: '/vendeur/login', name: 'app_vendeur_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard', [
                'role' => 'vendeur'
            ]);
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('vendeur_login/index.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/vendeur/inscription', name: 'app_vendeur_sigin')]
    public function sigin(Request $request, EntityManagerInterface $em): Response
    {
        $success = "";
        $error = "";

        $vendeur = new Vendeur();
        $form = $this->createForm(VendeurType::class, $vendeur)->handleRequest($request);

        try {

            if ($form->isSubmitted() && $form->isValid()) {

                $vendeur->setAdresse("");
                // $vendeur->setTel("");

                // 1. write the http protocol
                $full_url = "http://";
                // 2. check if your server use HTTPS
                if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                    $full_url = "https://";
                }
                $profile =  $full_url . $_SERVER["HTTP_HOST"] . "/defaults/profil-shop.jpg";

                
                $vendeur->setDescription("");
                $vendeur->setImageProfile($profile);
                $vendeur->setPhotoCouverture($profile);
                $vendeur->setPassword(password_hash($form->get('password')->getData(), PASSWORD_DEFAULT));
                // $vendeur->setPhoto("");

                $em->persist($vendeur);
                $em->flush();

                $success = "Inscription reussie";
            }
        } catch (Exception $e) {

            $error = "Email deja pris";
        }

        return $this->render('vendeur_login/inscription.html.twig', [
            'form' => $form->createView(),
            'success' => $success,
            'error' => $error
        ]);
    }

    #[Route(path: '/vendeur/recuperation-compte', name: 'app_vendeur_recuperation')]
    public function recuperation(Request $request, EntityManagerInterface $em): Response
    {
        return $this->render('vendeur_login/recuperation.html.twig', []);
    }
}
