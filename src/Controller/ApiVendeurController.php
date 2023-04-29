<?php

namespace App\Controller;

use App\Entity\Createur;
use App\Entity\Vendeur;
use App\Repository\VendeurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/v1', methods: ['POST', "GET"])]
class ApiVendeurController extends AbstractController
{
    #[Route('/liste/vendeur', name: 'api_app_liste_vendeur', methods:['GET'])]
    public function liste_vendeur(VendeurRepository $vendeur): JsonResponse
    {
        $final = [];
        $vendeurs = $vendeur->findAll();

        foreach ($vendeurs as $vendeur ) {
            array_push($final, [
                'id' => $vendeur->getId(),
                'Logo' => $vendeur->getImageProfile(),
                'nom' => $vendeur->getNom(),
                'email' => $vendeur->getEmail(),
                'createur' => $vendeur->getCreateur()->getNom() . ' ' . $vendeur->getCreateur()->getPrenom(),
                'createur_id' => $vendeur->getCreateur()->getId()
            ]);
        }
        return $this->json([
            'success' => 'success',
            'message' => $final,
        ]);
    }

    #[Route('/creation/vendeur', name: 'api_app_creation_vendeur', methods:['POST'])]
    public function creation_vendeur(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), false);

       try {
        $source = fopen($data->logo, 'r');

        $nomUnique = md5(uniqid());

        $destination = fopen($this->getParameter('image_vendeurs_directory') . '/' . $nomUnique . '.png', 'w');

        stream_copy_to_stream($source, $destination);

        fclose($source);
        fclose($destination);

        // 1. write the http protocol
        $full_url = "http://";

        // 2. check if your server use HTTPS
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") $full_url = "https://";

        $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/vendeurs/" . $nomUnique . '.png';

        if ( empty( $data->createur )) {
            return $this->json([
                'code' => 'error',
                'message' => 'createur manquant'
            ]);
        }

        $vendeur = new Vendeur();
        $vendeur->setNom( $data->nom );
        $vendeur->setEmail( $data->email );
        $vendeur->setDescription( $data->description);
        $vendeur->setImageProfile( $lien);
        $vendeur->setPhotoCouverture( $lien );
        $vendeur->setMobile( $data->mobile);
        $vendeur->setCreateur( $em->getRepository(Createur::class)->find($data->createur) );
        $vendeur->setAdresse( $data->adresse);
        $vendeur->setPays( $data->pays);
        $vendeur->setPassword( password_hash($data->password, PASSWORD_DEFAULT));
        $em->persist( $vendeur );

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
