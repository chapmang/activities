<?php
declare(strict_types=1);
namespace App\Presentation\Web\Pub\Controller;

use App\Domain\Services\RideServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RideController extends AbstractController
{

    /**
     * @var RideServices
     */
    private $rideService;

    public function __construct(RideServices $rideServices)
    {
        $this->rideService = $rideServices;
    }

    /**
     * @Route("/ride", name="ride_list", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function listRide(Request $request)
    {
        $searchTerm = $request->query->get('q');
        $pageNumber = $request->query->getInt('page', 1);
        $pagination = $this->rideService->getPaginatedSearchResults($searchTerm, $pageNumber);

        return $this->render('@Pub/ride/list.html.twig', [
            'pagination' => $pagination
        ]);
    }


}