<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PpolitiqueLivraisonController extends AbstractController
{
    #[Route('/politique-livraison', name: 'app_ppolitique_livraison')]
    public function index(): Response
    {
        return $this->render('ppolitique_livraison/index.html.twig', [
            'controller_name' => 'PpolitiqueLivraisonController',
        ]);
    }
}
