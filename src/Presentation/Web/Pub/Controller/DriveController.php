<?php
declare(strict_types=1);
namespace App\Presentation\Web\Pub\Controller;


use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DriveController extends AbstractController
{
    public function __construct()
    {
    }

    /**
     * @Route("/drive", name="drive_list", methods={"GET"})
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function listDrives(Request $request, PaginatorInterface $paginator)
    {
        $searchTerm = $request->query->get('q');
        $pageNumber = $request->query->getInt('page', 1);
        //$pagination = $this->walkService->getPaginatedSearchResults($searchTerm, $pageNumber);

        return $this->render('@Pub/activities/homepage.html.twig', [
            'pagination' => $pagination
        ]);
    }
}