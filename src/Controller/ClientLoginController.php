<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientLoginController extends AbstractController
{
    #[Route('/client-login', name: 'app_client_login')]
    public function index(): Response
    {
        return $this->render('client_login/index.html.twig', [
            'controller_name' => 'ClientLoginController',
        ]);
    }
}
