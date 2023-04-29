<?php

namespace App\Controller;

use App\Entity\Attribut;
use App\Entity\SousAttribut;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/attribut', name: 'api_attribut_')]
class ApiAttributController extends AbstractController
{
    #[Route('/add/sous/attribut', name: 'add_sous_attribut_by_api', methods: ['POST'])]
    public function add_sous_attribut(Request $request, EntityManagerInterface $em): Response
    {

        $data = json_decode($request->getContent(), false);

        if (isset($data->idAttribut) and !empty($data->idAttribut)) {


            $sousAttribut = new SousAttribut();

            if (isset($data->titre) and !empty($data->titre)) {

                $sousAttribut->setTitre($data->titre);
            }


            if (isset($data->valeur) and !empty($data->valeur)) {

                $sousAttribut->setValeur($data->valeur);
            }




            $attribut = $em->getRepository(Attribut::class)->find($data->idAttribut);

            $sousAttribut->setAttribut($attribut);

            $em->persist($sousAttribut);

            $em->flush();

            //return des sous attribut lie a cette attribut

            $sousAttributrecus = $attribut->getSousAttributs();

            $sousAttributFinal = [];


            foreach ($sousAttributrecus as $sous) {

                array_push($sousAttributFinal, [
                    'idAttribut' => $data->idAttribut,
                    'id' => $sous->getId(),
                    'titre' => $sous->getTitre(),
                    'valeur' => $sous->getValeur()
                ]);
            }

            return $this->json([
                'code' => 'success',
                'message' => $sousAttributFinal
            ]);
        } else {

            return $this->json(['code' => 'Erreur', 'message' => 'pas d\'insertion']);
        }
    }


    #[Route('/delete', name: 'delete_sous_attribut_by_api', methods: ['POST'])]
    public function delete_sous_attribut(Request $request, EntityManagerInterface $em): Response
    {

        $data = json_decode($request->getContent(), false);

        if (isset($data->idAttribut) and !empty($data->idAttribut)) {

            if (isset($data->idTerme) and !empty($data->idTerme)) {

                $term = $em->getRepository(SousAttribut::class)->find($data->idTerme);

                $em->remove($term);

                $em->flush();
            }


            $attribut = $em->getRepository(Attribut::class)->find($data->idAttribut);


            //return des sous attribut lie a cette attribut

            $sousAttributrecus = $attribut->getSousAttributs();

            $sousAttributFinal = [];


            foreach ($sousAttributrecus as $sous) {

                array_push($sousAttributFinal, [
                    'id' => $sous->getId(),
                    'titre' => $sous->getTitre(),
                    'valeur' => $sous->getValeur()
                ]);
            }

            return $this->json([
                'code' => 'success',
                'message' => $sousAttributFinal
            ]);
        } else {

            return $this->json(['code' => 'Erreur', 'message' => 'La suppression a echoue']);
        }
    }


    #[Route('/update', name: 'update_sous_attribut_by_api', methods: ['POST'])]
    public function update_sous_attribut(Request $request, EntityManagerInterface $em): Response
    {

        $data = json_decode($request->getContent(), false);

        if (isset($data->idAttribut) and !empty($data->idAttribut)) {

            if (isset($data->idTerme) and !empty($data->idTerme)) {

                $term = $em->getRepository(SousAttribut::class)->find($data->idTerme);

                if (isset($data->titre) and !empty($data->titre)) {

                    $term->setTitre($data->titre);
                }

                if (isset($data->valeur) and !empty($data->valeur)) {

                    $term->setValeur($data->valeur);
                }

                $em->flush();
            }


            $attribut = $em->getRepository(Attribut::class)->find($data->idAttribut);


            //return des sous attribut lie a cette attribut

            $sousAttributrecus = $attribut->getSousAttributs();

            $sousAttributFinal = [];


            foreach ($sousAttributrecus as $sous) {

                array_push($sousAttributFinal, [
                    'id' => $sous->getId(),
                    'titre' => $sous->getTitre(),
                    'valeur' => $sous->getValeur()
                ]);
            }

            return $this->json([
                'code' => 'success',
                'message' => $sousAttributFinal
            ]);
        } else {

            return $this->json(['code' => 'Erreur', 'message' => 'La modification a echoue']);
        }
    }
}
