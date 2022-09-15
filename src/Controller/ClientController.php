<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ClientController extends AbstractController
{
    #[Route('/api/clients', name: 'listClients', methods:['GET'])]
    public function getAllClients(ClientRepository $clientRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $page=$request->get('page',1);
        $limit=$request->get('limit', 2);
        $clientList = $clientRepository->findAllWithPagination($page, $limit);
        $context = SerializationContext::create()->setGroups(['getAllClients']);
        $jsonClientList=$serializer->serialize($clientList,'json',$context);

        return new JsonResponse($jsonClientList, Response::HTTP_OK,[],true);
    }

    #[Route('/api/clients/{id}', name: 'detailClient', methods:['GET'])]
    public function getDetailClient(Client $client, SerializerInterface $serializer): JsonResponse
    {
        $context = SerializationContext::create()->setGroups(['getAllClients']);
        $jsonClient = $serializer->serialize($client, 'json', $context);
        return new JsonResponse($jsonClient, Response::HTTP_OK, [], true);
    }

    #[Route('/api/clients/{id}', name: 'deleteClient', methods: ['DELETE'])]
    public function deleteClient(Client $client, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($client);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }


    #[Route('/api/clients', name: 'createClient', methods: ['POST'])]
    public function createClient(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse
    {
        $user=$this->getUser();

        $client=$serializer->deserialize($request->getContent(), Client::class,'json');
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
        $em->persist($client);
        $em->flush();

        $context = SerializationContext::create()->setGroups(['getAllClients']);
        $jsonClient=$serializer->serialize($client, 'json', $context);

        return new JsonResponse($jsonClient, Response::HTTP_CREATED, [], true);
    }

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
    ): JsonResponse
    {
        $newDataClient=$serializer->deserialize($request->getContent(), Client::class, 'json');

        $id=$request->get('id');

        if (!$currentClient= $clientRepository->find($id)){
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }
       if ($newDataClient->getEmail()){
            $currentClient->setEmail($newDataClient->getEmail());
       }
       if($newDataClient->getTown()){
            $currentClient->setTown($newDataClient->getTown());
       }
       if($newDataClient->getPostalCode()){
            $currentClient->setPostalCode($newDataClient->getPostalCode());
       }
       if($newDataClient->getNameStreet()){
            $currentClient->setNameStreet($newDataClient->getNameStreet());
       }
       if($newDataClient->getTypeStreet()){
            $currentClient->setTypeStreet($newDataClient->getTypeStreet());
       }
       if($newDataClient->getName()) {
           $currentClient->setName($newDataClient->getName());
       }
       if($newDataClient->getNumberStreet()){
        $currentClient->setNumberStreet($newDataClient->getNumberStreet());
       }
       if($newDataClient->getSurname()){
        $currentClient->setSurname($newDataClient->getSurname());
       }

        $errors = $validator->validate($currentClient);
        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($currentClient);
        $em->flush();
        $cache->invalidateTags(["clientsCache"]);


        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }


}
