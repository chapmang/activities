<?php
declare(strict_types=1);
namespace App\Presentation\Web\Pub\Controller;

use App\Domain\Services\DriveServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DriveController extends AbstractController
{
    /**
     * @var DriveServices
     */
    private $driveService;

    public function __construct(DriveServices $driveServices)
    {
        $this->driveService = $driveServices;
    }

    /**
     * @Route("/drive", name="drive_list", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function listDrives(Request $request)
    {
        $searchTerm = $request->query->get('q');
        $pageNumber = $request->query->getInt('page', 1);
        $pagination = $this->driveService->getPaginatedSearchResults($searchTerm, $pageNumber);

        return $this->render('@Pub/drive/list.html.twig', [
            'pagination' => $pagination
        ]);
    }

}