<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

#[Route('api/v1', methods: ['POST', "GET"])]
class ApiClientController extends AbstractController
{
    #[Route('/auth/client', name: 'app_liste_client', methods: ['POST'])]
    public function auth_client(ClientRepository $Client, Request $request ): JsonResponse
    {
        $data = json_decode($request->getContent(), false);

        // Load the user from your application's user provider
        $user = $Client->findOneBy([
            'email' => $data->email
        ]);

        if (!$user) {
            return $this->json([
                'code' => 'error',
                'message' => 'compte inexistant'
            ]);
        }

        // Check if the user's password is correct
        if ( password_verify($data->password , $user->getPassword())) {
            return $this->json([
                'code' => 'error',
                'message' => 'Identifiant ou mot de passe incorrect'
            ]);
        }


        // Create an authentication token with the user's information and the "main" firewall name
        $token = new UsernamePasswordToken($user, $data->password, ['main'], $user->getRoles());

        // Authenticate the token
        // $authenticatedToken = $authenticationManager->authenticate($token);

        // Set the authenticated token to the TokenStorage to make the user authenticated for subsequent requests
        // $tokenStorage->setToken($authenticatedToken);
    }

    #[Route('/liste/client', name: 'app_liste_client', methods: ['GET'])]
    public function liste_client(ClientRepository $Client): JsonResponse
    {
        return $this->json([
            'success' => 'success',
            'message' => $Client->findAll(),
        ]);
    }

    #[Route('/creation/client', name: 'app_creation_client', methods: ['POST'])]
    public function creation_client(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), false);

        try {
            $source = fopen($data->logo, 'r');

            $nomUnique = md5(uniqid());

            $destination = fopen($this->getParameter('image_clients_directory') . '/' . $nomUnique . '.png', 'w');

            stream_copy_to_stream($source, $destination);

            fclose($source);
            fclose($destination);

            // 1. write the http protocol
            $full_url = "http://";

            // 2. check if your server use HTTPS
            if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") $full_url = "https://";

            $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/clients/" . $nomUnique . '.png';

            $Client = new Client();
            $Client->setNom($data->nom);
            $Client->setPhoto($lien);
            $Client->setPrenom($data->prenom);
            $Client->setAdresse($data->adresse);
            $Client->setEmail($data->email);
            $Client->setPassword(password_hash($data->password, PASSWORD_DEFAULT));
            $Client->setTel($data->mobile);

            $em->persist($Client);

            $em->flush();

            return $this->json([
                'code' => 'success',
                'message' => 'insertion effectuee',
            ]);
        } catch (\Throwable $th) {
            return $this->json([
                'code' => 'error',
                'message' => $th->getMessage(),
            ]);
        }
    }
}
