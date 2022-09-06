<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    #[Route('/api/users', name: 'listUsers', methods:['GET'])]
    public function getAllUsers(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $userList = $userRepository->findAll();
        $jsonUserList=$serializer->serialize($userList,'json',['groups'=> 'getAllUsers']);

        return new JsonResponse($jsonUserList, Response::HTTP_OK,[],true);
    }

    #[Route('/api/users/{id}', name: 'detailUser', methods:['GET'])]
    public function getDetailUser(User $user, SerializerInterface $serializer): JsonResponse
    {
        $jsonUser = $serializer->serialize($user, 'json',['groups'=> 'getAllUsers']);
        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }


    #[Route('/api/users/{id}', name: 'deleteUser', methods: ['DELETE'])]
    public function deleteUser(User $user, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($user);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }


    #[Route('/api/users', name: 'createUser', methods: ['POST'])]
    public function createUser(Request $request,  SerializerInterface $serializer, EntityManagerInterface $em, ClientRepository $clientRepository): JsonResponse
    {
        $user=$serializer->deserialize($request->getContent(), User::class,'json');


        $content= $request->toArray();
        $client_id=$content['client_id'] ?? -1;
        $user->setClient($clientRepository->find($client_id));
        $em->persist($user);
        $em->flush();

        $jsonUser=$serializer->serialize($user, 'json', ['groups'=> 'getAllUsers']);
        return new JsonResponse($jsonUser, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/users/{id}', name: 'updateUser', methods: ['PUT'])]
    public function updateUser(Request $request,  User $currentUser, SerializerInterface $serializer, EntityManagerInterface $em, ClientRepository $clientRepository): JsonResponse
    {
        $updateUser=$serializer->deserialize($request->getContent(), User::class,'json', [AbstractNormalizer::OBJECT_TO_POPULATE=>$currentUser]);


        $content= $request->toArray();
        $client_id=$content['client_id'] ?? -1;
        $updateUser->setClient($clientRepository->find($client_id));
        $em->persist($updateUser);
        $em->flush();


        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }


}
