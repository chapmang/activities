<?php
declare(strict_types=1);
namespace App\Presentation\Web\Pub\Controller;

use App\Domain\Services\PoiServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PoiController extends AbstractController
{

    /**
     * @var PoiServices
     */
    private $poiService;

    public function __construct(PoiServices $poiServices)
    {
        $this->poiService = $poiServices;
    }

    /**
     * @Route("/poi", name="poi_list", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function listPoi(Request $request)
    {
        $searchTerm = $request->query->get('q');
        $pageNumber = $request->query->getInt('page', 1);
        $pagination = $this->poiService->getPaginatedSearchResults($searchTerm, $pageNumber);

        return $this->render('@Pub/poi/list.html.twig', [
            'pagination' => $pagination
        ]);
    }

}