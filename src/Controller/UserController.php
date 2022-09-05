<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    #[Route('/api/users', name: 'app_user')]
    public function getAllPhones(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $userList = $userRepository->findAll();
        $jsonUserList=$serializer->serialize($userList,'json');

        return new JsonResponse($jsonUserList, Response::HTTP_OK,[],true);
    }

    #[Route('/api/users/{id}', name: 'app_phone')]
    public function getDetailPhone(User $user, SerializerInterface $serializer): JsonResponse
    {
        $jsonUser = $serializer->serialize($user, 'json');
        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }
}
