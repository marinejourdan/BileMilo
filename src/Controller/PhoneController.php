<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PhoneController extends AbstractController
{
    #[Route('/api/phones', name: 'app_phone',methods:['GET'])]
    public function getAllPhones(PhoneRepository $phoneRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $page=$request->get('page',1);
        $limit=$request->get('limit', 2);
        $phoneList = $phoneRepository->findAllWithPagination($page, $limit);
        $jsonPhoneList=$serializer->serialize($phoneList,'json');

        return new JsonResponse($jsonPhoneList, Response::HTTP_OK,[],true);
    }

    #[Route('/api/phones/{id}', name: 'app_phone_id',methods:['GET'])]
    public function getDetailPhone(Phone $phone, SerializerInterface $serializer): JsonResponse
    {
        $jsonPhone = $serializer->serialize($phone, 'json');
        return new JsonResponse($jsonPhone, Response::HTTP_OK, [], true);
    }
}
