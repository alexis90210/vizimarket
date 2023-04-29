<?php

namespace App\Controller;

use App\Entity\Commandes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order/{param}', name: 'app_order')]
    public function index(string $param, EntityManagerInterface $em): Response
    {
        $param = explode('-', $param);

        $idCommande = $param[1];

        $commandeConcernee = $em->getRepository(Commandes::class)->find($idCommande);

        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
            'commande' => $commandeConcernee
        ]);
    }
}
