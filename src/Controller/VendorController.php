<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VendorController extends AbstractController
{
    #[Route('/les-vendeurs', name: 'app_vendor')]
    public function index(): Response
    {
        return $this->render('les_vendeurs/index.html.twig', [
            'controller_name' => 'VendorController',
        ]);
    }
}
