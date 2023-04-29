<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Error404Controller extends AbstractController
{
    #[Route('/error-404', name: 'app_error404')]
    public function notFound(): Response
    {
        return $this->render('error404/error404.html.twig', [
            'controller_name' => 'Error404Controller',
        ]);
    }
}
