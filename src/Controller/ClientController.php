<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ClientController extends AbstractController
{
    #[Route('/api/clients', name: 'listClients', methods:['GET'])]
    public function getAllClients(
        ClientRepository $clientRepository,
        SerializerInterface $serializer,
        Request $request,
        TagAwareCacheInterface $cache
    ): JsonResponse
    {
        $page=$request->get('page',1);
        $limit=$request->get('limit', 1);

        $idCache="getAllClients".$page."-".$limit;

        $jsonClientList=$cache->get($idCache, function (ItemInterface $item) use ($clientRepository, $page, $limit, $serializer){
            echo ("l'element n'est pas encore en cache");
            $item->tag("clientsCache");
            $clientList = $clientRepository->findAllWithPagination($page, $limit);
            $jsonClientList=$serializer->serialize($clientList,'json',['groups'=> 'getAllClients']);
            return $jsonClientList;
        });
        return new JsonResponse($jsonClientList, Response::HTTP_OK,[],true);
    }

    #[Route('/api/users/{id}', name: 'detailClient', methods:['GET'])]
    public function getDetailClient(
        Client $client,
        SerializerInterface
        $serializer
    ): JsonResponse
    {
        $jsonClient = $serializer->serialize($user, 'json',['groups'=> 'getAllClients']);
        return new JsonResponse($jsonClient, Response::HTTP_OK, [], true);
    }

    #[Route('/api/clients/{id}', name: 'deleteClient', methods: ['DELETE'])]
    public function deleteClient(
        Client $client,
        EntityManagerInterface $em,
        TagAwareCacheInterface $cache
    ): JsonResponse
    {
        $cache->invalidateTags(["clientsCache"]);
        $em->remove($client);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }


    #[Route('/api/clients', name: 'createClient', methods: ['POST'])]
    public function createClient(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        TagAwareCacheInterface $cache
    ): JsonResponse
    {
        $user=$this->getUser();
        $client=$serializer->deserialize($request->getContent(), Client::class,'json');
        $content= $request->toArray();
        $errors= $validator->validate($client);

        if ($errors->count() > 0){
            return new JsonResponse(
                $serializer->serialize($errors, 'json'),
                JsonResponse::HTTP_BAD_REQUEST,
                [],
                true
            );
        }

        $client->setUser($user);
        $cache->invalidateTags(["clientsCache"]);
        $em->persist($client);
        $em->flush();
        $jsonClient=$serializer->serialize($client, 'json', ['groups'=> 'getAllClients']);
        return new JsonResponse($jsonClient, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/clients/{id}', name: 'updateClient', methods: ['PUT'])]
    public function updateClient(
        Request $request,
        Client $currentClient,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        TagAwareCacheInterface $cache
    ): JsonResponse
    {
        $user=$this->getUser();
        $updateClient=$serializer->deserialize($request->getContent(), Client::class,'json', [AbstractNormalizer::OBJECT_TO_POPULATE=>$currentClient]);
        $content= $request->toArray();

        $updateClient->setUser($user);
        $cache->invalidateTags(["clientsCache"]);
        $em->persist($updateClient);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
