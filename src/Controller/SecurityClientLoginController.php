<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityClientLoginController extends AbstractController
{
    #[Route(path: '/client/login', name: 'app_client_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {          
            return $this->redirectToRoute('app_compte');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('client_auth/index.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/client/inscription', name: 'app_client_sigin')]
    public function sigin(Request $request, EntityManagerInterface $em): Response
    {
        $success = "";
        $error = "";

        $client = new Client();
        $form = $this->createForm(ClientType::class, $client)->handleRequest($request);

       try {

        if ( $form->isSubmitted() && $form->isValid()) {

            $client->setAdresse("");
            $client->setTel("");
            $client->setPassword( password_hash($form->get('password')->getData(), PASSWORD_DEFAULT) );

             // 1. write the http protocol
             $full_url = "http://";
             // 2. check if your server use HTTPS
             if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                 $full_url = "https://";
             }
             $profile =  $full_url . $_SERVER["HTTP_HOST"] . "/defaults/user-profil.png";

            $client->setPhoto($profile);
  
            $em->persist( $client );
            $em->flush();          
            
            $success = "Inscription reussie";
  
          }


       } catch( Exception $e) {
        $error = "Email deja pris";
       }

        return $this->render('client_auth/inscription.html.twig', [
            'form' => $form->createView(),
            'success' => $success,
            'error' => $error
        ]);
    }

    #[Route(path: '/client/recuperation-compte', name: 'app_client_recuperation')]
    public function recuperation(Request $request, EntityManagerInterface $em): Response
    {
        return $this->render('client_auth/recuperation.html.twig', []);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('app_home');
    }
}
