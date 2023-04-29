<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreatorLoginController extends AbstractController
{
    #[Route('/createur-login', name: 'app_creator_login')]
    public function index(): Response
    {
        return $this->render('creator_login/index.html.twig', [
            'controller_name' => 'CreatorLoginController',
        ]);
    }
}
