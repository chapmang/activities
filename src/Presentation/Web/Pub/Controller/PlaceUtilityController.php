<?php


namespace App\Presentation\Web\Pub\Controller;


use App\Domain\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PlaceUtilityController extends AbstractController
{
    /**
     * @Route("/places", methods={"GET"}, name="places_api")
     * @param PlaceRepository $placeRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getPlacesApi(PlaceRepository $placeRepository)
    {
        $places = $placeRepository->findAll();

        return $this->json([
            'places' => $places
        ]);
    }
}