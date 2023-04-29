<?php

namespace App\Controller;

use App\Entity\Vazimarket;
use App\Form\VazimarketType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityAuthenticatorController extends AbstractController
{
    #[Route(path: '/vazimarket/login', name: 'app_vazimarket_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard', [
                'role' => 'vazimarket'
            ]);
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('vazimarket_login/index.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/vazimarket/inscription', name: 'app_vazimarket_sigin')]
    public function sigin(Request $request, EntityManagerInterface $em): Response
    {
        $success = "";
        $error = "";

        $vazimarket = new Vazimarket();
        $form = $this->createForm(VazimarketType::class, $vazimarket)->handleRequest($request);

       try {

        if ( $form->isSubmitted() && $form->isValid()) {

            $vazimarket->setPassword( password_hash($form->get('password')->getData(), PASSWORD_DEFAULT) );
    
            $em->persist( $vazimarket );
            $em->flush();          
            
            $success = "Inscription reussie";
  
          }

       } catch( \Exception $e) {
        $error = "Email deja pris";
       }

        return $this->render('vazimarket_login/inscription.html.twig', [
            'form' => $form->createView(),
            'success' => $success,
            'error' => $error
        ]);
    }

    #[Route(path: '/vazimarket/recuperation-compte', name: 'app_vazimarket_recuperation')]
    public function recuperation(Request $request, EntityManagerInterface $em): Response
    {
        return $this->render('vazimarket_login/recuperation.html.twig', []);
    }
}
