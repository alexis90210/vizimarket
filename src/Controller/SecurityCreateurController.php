<?php

namespace App\Controller;

use App\Entity\Createur;
use App\Form\CreateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityCreateurController extends AbstractController
{
    #[Route(path: '/createur/login', name: 'app_createur_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard', [
                'role' => 'createur'
            ]);
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('createur_login/index.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/createur/inscription', name: 'app_createur_sigin')]
    public function sigin(Request $request, EntityManagerInterface $em): Response
    {
        $success = "";
        $error = "";

        $createur = new Createur();
        $form = $this->createForm(CreateurType::class, $createur)->handleRequest($request);

       try {

        if ( $form->isSubmitted() && $form->isValid()) {

            $createur->setLogo("");

            $createur->setPassword( password_hash($form->get('password')->getData(), PASSWORD_DEFAULT) );
  
            $em->persist( $createur );
            $em->flush();          
            
            $success = "Inscription reussie";
  
          }

       } catch( \Exception $e) {
        $error = "Email deja pris";
       }

        return $this->render('createur_login/inscription.html.twig', [
            'form' => $form->createView(),
            'success' => $success,
            'error' => $error
        ]);
    }

    #[Route(path: '/createur/recuperation-compte', name: 'app_createur_recuperation')]
    public function recuperation(Request $request, EntityManagerInterface $em): Response
    {
        return $this->render('createur_login/recuperation.html.twig', []);
    }
}
