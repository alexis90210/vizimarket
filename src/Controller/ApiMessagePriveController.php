<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Vendeur;
use App\Entity\Conversation;
use App\Entity\MessagePrive;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Serializer\SerializerInterface as SerializerSerializerInterface;

#[Route('/api/message/prive', name: 'api_message_prive_')]
class ApiMessagePriveController extends AbstractController
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    #[Route('/send/{id}', name: 'send_message', methods: ['POST'])]
    public function sendMessage(Client $client, EntityManagerInterface $em, Request $request, ConversationRepository $conversationRepository, SessionInterface $session): Response
    {
        $data = $request->request->all();

        $idProduit = $data['idProduit'];

        $em->beginTransaction();

        try {
            //code...

            if (isset($data['idVendeur']) and !empty($data['idVendeur'])) {

                $vendeur = $em->getRepository(Vendeur::class)->find($data['idVendeur']);

                $verification = $conversationRepository->findBy(['client' => $client, 'vendeur' => $vendeur]);

                if (count($verification) > 0 and count($verification) == 1) {
                    //On ajoute un nouveau message

                    $verification1 = $conversationRepository->findOneBy(['client' => $client, 'vendeur' => $vendeur]);

                    $newMessagePrive = new MessagePrive();

                    $newMessagePrive->setContenu($data['contenu']);

                    $newMessagePrive->setDate(date('d/m/Y H:i:s'));

                    $newMessagePrive->setConversation($verification1);

                    $newMessagePrive->setSens(1);

                    $em->persist($newMessagePrive);

                    $em->flush();

                    $em->commit();

                    return $this->redirectToRoute('app_product', ['id' => $idProduit]);
                } else {
                    //On cree une nouvelle conversation

                    $newConversation = new Conversation();

                    $newConversation->setVendeur($vendeur);

                    $newConversation->setClient($client);

                    $newConversation->setDate(date('d/m/Y H:i:s'));

                    $em->persist($newConversation);

                    $em->flush();

                    $newMessagePrive = new MessagePrive();

                    if (isset($data['contenu']) and !empty($data['contenu'])) {

                        $newMessagePrive->setContenu($data['contenu']);

                        $newMessagePrive->setSens(1);

                        $newMessagePrive->setDate(date('d/m/Y H:i:s'));

                        $newMessagePrive->setConversation($newConversation);

                        $em->persist($newMessagePrive);

                        $em->flush();

                        //$session->getFlashBag()->add('success', 'Votre message a bien ete envoye.');

                        $em->commit();

                        return $this->redirectToRoute('app_product', ['id' => $idProduit]);
                    }
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
            $em->rollback();
            dd($th);
        }
    }

    #[Route('/get/conversation/for/one/client', name: 'get_conversation_for_one_client', methods: ['POST'])]
    public function get_conversation_for_one_client(EntityManagerInterface $em, Request $request, SerializerSerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), false);

        //return $this->json(['data' => $data]);

        if (empty($data->idConversation)) {

            return $this->json([
                'code' => 'erreur',
                'message' => 'il manque l\'id de la conversation'
            ]);
        }

        //return $this->json(['debiug' => $data->idConversation]);


        $conversationConcernee = $em->getRepository(Conversation::class)->find($data->idConversation);

        $jsonConversations = $serializer->serialize($conversationConcernee, 'json', ['groups' => 'getConversations']);


        return new JsonResponse($jsonConversations, Response::HTTP_OK, [], true);
    }


    #[Route('/get/current/conversation', name: 'get_current_conversation_for_one_client', methods: ['POST'])]
    public function get_current_conversation_for_one_client(EntityManagerInterface $em, Request $request, SerializerSerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), false);

        //return $this->json(['data' => $data]);

        if (empty($data->idConversation)) {

            return $this->json([
                'code' => 'erreur',
                'message' => 'il manque l\'id de la conversation'
            ]);
        }

        if (empty($data->idLastMessage)) {

            return $this->json([
                'code' => 'erreur',
                'message' => 'il manque l\'id du dernier message'
            ]);
        }


        // $conversationConcernee = $em->getRepository(Conversation::class)->find($data->idConversation);

        // $dataSerialise = $serializer->serialize($conversationConcernee, 'json', ['groups' => 'getConversations']);

        // $clientConcerne = $dataSerialise['client'];
        $conn = $em->getConnection();

        $sql = '
        SELECT mp.*, cv.client_id,client.nom,client.prenom,client.photo FROM message_prive as mp inner Join conversation as cv On  mp.conversation_id=cv.id INNER JOIN client On client.id=cv.client_id where mp.id > :id and mp.conversation_id = :conversation';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $data->idLastMessage, 'conversation' => $data->idConversation]);

        // returns an array of arrays (i.e. a raw data set)
        $messagesFinaux = $resultSet->fetchAllAssociative();

        return $this->json(['code' => 'debug', 'message' => $messagesFinaux]);


        //$jsonConversations = $serializer->serialize($conversationConcernee, 'json', ['groups' => 'getConversations']);


        //return new JsonResponse($jsonConversations, Response::HTTP_OK, [], true);
    }


    #[Route('/add/message', name: 'add_message_by_vendeur', methods: ['POST'])]
    public function add_message_by_vendeur(EntityManagerInterface $em, Request $request, SerializerSerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), false);

        //return $this->json(['data' => $data]);

        if (empty($data->idConversation)) {

            return $this->json([
                'code' => 'erreur',
                'message' => 'il manque l\'id de la conversation'
            ]);
        }

        $conversationConcernee = $em->getRepository(Conversation::class)->find($data->idConversation);

        if (!empty($data->contenu)) {

            $newMessagePrive = new MessagePrive();

            $newMessagePrive->setContenu($data->contenu);

            $newMessagePrive->setDate(date('d/m/Y H:i:s'));

            $newMessagePrive->setSens(2);

            $newMessagePrive->setConversation($conversationConcernee);

            $em->persist($newMessagePrive);

            $em->flush();
        }

        return $this->json(['code' => 'success', 'message' => 'votre message a bien ete envoye avec success']);
    }
}
