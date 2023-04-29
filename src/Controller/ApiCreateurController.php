<?php

namespace App\Controller;

use App\Entity\ConfigurationCreateur;
use App\Entity\Createur;
use App\Repository\ConfigurationCreateurRepository;
use App\Repository\CreateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/v1', methods: ['POST', "GET"])]
class ApiCreateurController extends AbstractController
{
    #[Route('/liste/createur', name: 'app_liste_createur', methods: ['GET'])]
    public function liste_createur(CreateurRepository $createur): JsonResponse
    {
        return $this->json([
            'code' => 'success',
            'message' => $createur->findAll(),
        ]);
    }

    #[Route('/create/createur/design', name: 'app_create_createur_design', methods: ['POST'])]
    public function create_createur_design(Request $request, CreateurRepository $createurRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), false);

        if (empty($data->createur_id)) return $this->json(['code' => 'success', 'message' => "Createur non reconnu"]);

        $createur = $createurRepository->find($data->createur_id);
        $last = $createur->getConfigurationCreateur();

        $config = new ConfigurationCreateur();

        if ($last) {
            $config = $last;
        } else {
            $config->setCreateur($createur);
        }

        $config->setCouleurFond($data->couleur_fond ?? $config->getCouleurFond());
        $config->setCouleurHeader($data->couleur_header ?? $config->getCouleurHeader());
        $config->setCouleurFooter($data->couleur_footer ?? $config->getCouleurFooter());
        $config->setFontFamily($data->police ?? $config->getFontFamily());
        $config->setFontSize($data->taille ?? $config->getFontSize());

        if (!$last) {
            $em->persist($config);
        }

        $em->flush();

        return $this->json([
            'code' => 'success',
            'message' =>  empty($data->configuration_id) ? 'configuration effectuee' : 'Mise a jour reussie',
        ]);
    }

    #[Route('/create/createur/contenu', name: 'app_create_createur_contenu', methods: ['POST'])]
    public function create_createur_contenu(Request $request, CreateurRepository $createurRepository, ConfigurationCreateurRepository $configurationCreateurRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), false);

        if (empty($data->createur_id)) return $this->json(['code' => 'success', 'message' => "Createur non reconnu"]);

        $createur = $createurRepository->find($data->createur_id);
        $last = $createur->getConfigurationCreateur();

        $config = new ConfigurationCreateur();

        if ($last) {
            $config = $last;
        } else {
            $config->setCreateur($createur);
        }

        $config->setConditionGeneraleUtilisation($data->cgu ?? $config->getConditionGeneraleUtilisation());
        $config->setConditionGeneraleVente($data->cgv ?? $config->getConditionGeneraleVente());
        $config->setMentionsLegales($data->mention_legale ?? $config->getMentionsLegales());
        $config->setPolitiqueConfidentialite($data->politique ?? $config->getPolitiqueConfidentialite());

        if (!$last) {
            $em->persist($config);
        }

        $em->flush();

        return $this->json([
            'code' => 'success',
            'message' =>  empty($data->configuration_id) ? 'configuration effectuee' : 'Mise a jour reussie',
        ]);
    }

    #[Route('/creation/createur', name: 'app_creation_createur', methods: ['POST'])]
    public function creation_createur(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), false);

        try {
            $source = fopen($data->logo, 'r');

            $nomUnique = md5(uniqid());

            $destination = fopen($this->getParameter('image_createurs_directory') . '/' . $nomUnique . '.png', 'w');

            stream_copy_to_stream($source, $destination);

            fclose($source);
            fclose($destination);

            // 1. write the http protocol
            $full_url = "http://";

            // 2. check if your server use HTTPS
            if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") $full_url = "https://";

            $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/createurs/" . $nomUnique . '.png';

            $createur = new Createur();
            $createur->setNom($data->nom);
            $createur->setLogo($lien);
            $createur->setPrenom($data->prenom);
            $createur->setCommissionVendeur($data->commission);
            $createur->setEmail($data->email);
            $createur->setPassword(password_hash($data->password, PASSWORD_DEFAULT));
            $createur->setTypeValidationVendeur($data->typeValidationVendeur);

            $em->persist($createur);

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

    #[Route('/load/createur/configuration/{id}', name: 'app_load_createur_configuration', methods: ['GET'])]
    public function app_load_createur_configuration(Request $request, $id, EntityManagerInterface $em): JsonResponse
    {
        // $configurations = $em->getRepository(ConfigurationCreateur::class)->findBy(['Createur' => $createur]);

        $conn = $em->getConnection();

        $sql = "SELECT * FROM configuration_createur where createur_id=:id";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);

        $configurationsFinales = $resultSet->fetchAllAssociative();


        return $this->json(['code' => 'success', 'message' => $configurationsFinales]);
    }
}