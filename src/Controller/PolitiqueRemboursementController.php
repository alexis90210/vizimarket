<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PolitiqueRemboursementController extends AbstractController
{
    #[Route('/politique-remboursement', name: 'app_politique_remboursement')]
    public function index(): Response
    {
        return $this->render('politique_remboursement/index.html.twig', [
            'controller_name' => 'PolitiqueRemboursementController',
        ]);
    }
}
