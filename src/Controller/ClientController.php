<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ClientController extends AbstractController
{
    /**
     * Cette méthode permet de récupérer l'ensemble des clients.
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne la liste des clients",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Client::class, groups={"getAllClients"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="La page que l'on veut récupérer",
     *     @OA\Schema(type="int")
     * )
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     description="Le nombre d'éléments que l'on veut récupérer",
     *     @OA\Schema(type="int")
     * )
     *
     * @throws InvalidArgumentException
     */
    #[Route('/api/clients', name: 'listClients', methods: ['GET'])]
    public function getAllClients(
        TagAwareCacheInterface $cache,
        ClientRepository $clientRepository,
        SerializerInterface $serializer,
        Request $request
    ): JsonResponse {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 6);
        $user = $this->getUser();

        $idCache = 'getAllClients'.$page.'-'.$limit;
        $clientList = $cache->get($idCache, function (ItemInterface $item) use ($user, $clientRepository, $page, $limit) {
            echo "l element n'est pas encore en cache";
            $item->tag('ClientsCache');

            return $clientRepository->findByUserWithPagination($user, $page, $limit);
        });

        $context = SerializationContext::create()->setGroups(['getAllClients']);
        $jsonClientList = $serializer->serialize($clientList, 'json', $context);

        return new JsonResponse($jsonClientList, Response::HTTP_OK, [], true);
    }

    /**
     * Cette méthode permet de récupérer le détail d'un client.
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne le détail d'un client",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Client::class, groups={"getAllClients"}))
     *     )
     * )
     *
     * @throws InvalidArgumentException
     */
    #[Route('/api/clients/{id}', name: 'detailClient', methods: ['GET'])]
    public function getDetailClient(
        Client $client,
        SerializerInterface $serializer
    ): JsonResponse {
        $this->denyAccessUnlessGranted('CLIENT_ACCESS', $client);
        $context = SerializationContext::create()->setGroups(['getAllClients']);
        $context->setVersion('1.0');
        $jsonClient = $serializer->serialize($client, 'json', $context);

        return new JsonResponse($jsonClient, Response::HTTP_OK, [], true);
    }

    #[Route('/api/clients/{id}', name: 'deleteClient', methods: ['DELETE'])]
    public function deleteClient(
        Client $client,
        EntityManagerInterface $em
    ): JsonResponse {
        $this->denyAccessUnlessGranted('CLIENT_ACCESS', $client);
        $em->remove($client);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Post(
     *     path="/api/clients",
     *     method="POST"
     * )
     * @OA\RequestBody(
     *     description="Ajouter un client",
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 description="First name of the new user",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="surname",
     *                 description="Last name of the new user",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 description="Email of the new user",
     *                 type="string"
     *             ),
     *            @OA\Property(
     *                 property="numberStreet",
     *                 description="Email of the new user",
     *                 type="int"
     *             ),
     *            @OA\Property(
     *                 property="typeStreet",
     *                 description="Email of the new user",
     *                 type="string"
     *             ),
     *            @OA\Property(
     *                 property="nameStreet",
     *                 description="Email of the new user",
     *                 type="string"
     *             ),
     *           @OA\Property(
     *                 property="Town",
     *                 description="Email of the new user",
     *                 type="string"
     *             ),
     *          @OA\Property(
     *                 property="postal_code",
     *                 description="Id of mobile phone",
     *                 type="integer"
     *             ),
     *         )
     *     )
     * )
     * @OA\Response(
     *     response=201,
     *     description="OK"
     * )
     */
    #[Route('/api/clients', name: 'createClient', methods: ['POST'])]
    public function createClient(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        TagAwareCacheInterface $cache,
        Client $client
    ): JsonResponse {
        $this->denyAccessUnlessGranted('CLIENT_ACCESS', $client);
        $user = $this->getUser();

        $client = $serializer->deserialize($request->getContent(), Client::class, 'json');
        $errors = $validator->validate($client);

        if ($errors->count() > 0) {
            return new JsonResponse(
                $serializer->serialize($errors, 'json'),
                JsonResponse::HTTP_BAD_REQUEST,
                [],
                true
            );
        }
        $client->setUser($user);
        $client->setComment('gnagna');
        $em->persist($client);
        $em->flush();
        $cache->invalidateTags(['getAllClients']);

        $context = SerializationContext::create()->setGroups(['getAllClients']);
        $jsonClient = $serializer->serialize($client, 'json', $context);

        return new JsonResponse($jsonClient, Response::HTTP_CREATED, [], true);
    }

    /**
     * @OA\Post(
     *     path="/api/clients",
     *     method="PUT"
     * )
     * @OA\RequestBody(
     *     description="Ajouter un client",
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 description="First name of the new user",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="surname",
     *                 description="Last name of the new user",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 description="Email of the new user",
     *                 type="string"
     *             ),
     *            @OA\Property(
     *                 property="numberStreet",
     *                 description="Email of the new user",
     *                 type="int"
     *             ),
     *            @OA\Property(
     *                 property="typeStreet",
     *                 description="Email of the new user",
     *                 type="string"
     *             ),
     *            @OA\Property(
     *                 property="nameStreet",
     *                 description="Email of the new user",
     *                 type="string"
     *             ),
     *           @OA\Property(
     *                 property="Town",
     *                 description="Email of the new user",
     *                 type="string"
     *             ),
     *          @OA\Property(
     *                 property="postal_code",
     *                 description="Id of mobile phone",
     *                 type="integer"
     *             ),
     *         )
     *     )
     * )
     * @OA\Response(
     *     response=201,
     *     description="OK"
     * )
     */
    #[Route('/api/clients/{id}', name: 'updateClient', methods: ['PUT'])]
    public function updateUser(
        Request $request,
        Client $currentClient,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        ValidatorInterface $validator,
        ClientRepository $clientRepository,
        TagAwareCacheInterface $cache
    ): JsonResponse {
        $newDataClient = $serializer->deserialize($request->getContent(), Client::class, 'json');

        $id = $request->get('id');

        if (!$currentClient = $clientRepository->find($id)) {
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }
        if ($newDataClient->getEmail()) {
            $currentClient->setEmail($newDataClient->getEmail());
        }
        if ($newDataClient->getTown()) {
            $currentClient->setTown($newDataClient->getTown());
        }
        if ($newDataClient->getPostalCode()) {
            $currentClient->setPostalCode($newDataClient->getPostalCode());
        }
        if ($newDataClient->getNameStreet()) {
            $currentClient->setNameStreet($newDataClient->getNameStreet());
        }
        if ($newDataClient->getTypeStreet()) {
            $currentClient->setTypeStreet($newDataClient->getTypeStreet());
        }
        if ($newDataClient->getName()) {
            $currentClient->setName($newDataClient->getName());
        }
        if ($newDataClient->getNumberStreet()) {
            $currentClient->setNumberStreet($newDataClient->getNumberStreet());
        }
        if ($newDataClient->getSurname()) {
            $currentClient->setSurname($newDataClient->getSurname());
        }

        $errors = $validator->validate($currentClient);
        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($currentClient);
        $em->flush();
        $cache->invalidateTags(['getAllClients']);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
