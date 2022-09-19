<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
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
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class PhoneController extends AbstractController
{
    /**
     * Cette méthode permet de récupérer l'ensemble des téléphones.
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne la liste des clients",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Phone::class))
     *     )
     * )
     *
     * @throws InvalidArgumentException
     */
    #[Route('/api/phones', name: 'app_phone', methods: ['GET'])]
    public function getAllPhones(
        PhoneRepository $phoneRepository,
        SerializerInterface $serializer,
        Request $request,
        TagAwareCacheInterface $cache
    ): JsonResponse {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 2);

        $idCache = 'getAllPhones'.$page.'-'.$limit;
        $phoneList = $cache->get($idCache, function (ItemInterface $item) use ($phoneRepository, $page, $limit) {
            echo "l element n'est pas encore en cache";
            $item->tag('phonesCache');

            return $phoneRepository->findAllWithPagination($page, $limit);
        });

        $context = SerializationContext::create()->setGroups(['getAllPhones']);
        $jsonPhoneList = $serializer->serialize($phoneList, 'json', $context);

        return new JsonResponse($jsonPhoneList, Response::HTTP_OK, [], true);
    }

    /**
     * Cette méthode permet de récupérer le détail d'un téléphone.
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne le détail d'un téléphone",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Phone::class))
     *     )
     * )
     *
     * @throws InvalidArgumentException
     */
    #[Route('/api/phones/{id}', name: 'detailPhone', methods: ['GET'])]
    public function getDetailPhone(
        Phone $phone,
        SerializerInterface $serializer
    ): JsonResponse {
        $jsonPhone = $serializer->serialize($phone, 'json');

        return new JsonResponse($jsonPhone, Response::HTTP_OK, [], true);
    }
}
