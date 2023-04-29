<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientEditType;
use App\Form\ClientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CompteController extends AbstractController
{
    #[Route('/client/compte', name: 'app_compte')]
    public function index(SessionInterface $session): Response
    {
        $session->set('user', $this->getUser());
        $session->save();

        return $this->render('compte/index.html.twig', [
            'currentClient' => $this->getUser(),
            'form' => '' // $form
        ]);
    }
}
