<?php


namespace App\Controller;


use App\Entity\CollectionContents;
use App\Repository\ActivityRepository;
use App\Repository\CollectionContentsRepository;
use App\Repository\CollectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CollectionContentsController extends AbstractController
{

    private $collectionContentsRepository;

    public function __construct(CollectionContentsRepository $collectionContentsRepository)
    {
        $this->collectionContentsRepository = $collectionContentsRepository;
    }

    /**
     * @Route("/collection/addactivity", name="collection_add_activity", methods={"POST"})
     * @param Request $request
     * @param CollectionRepository $collectionRepository
     * @param ActivityRepository $activityRepository
     */
    public function addActivity(Request $request, CollectionRepository $collectionRepository, ActivityRepository $activityRepository)
    {
        $collectionID = $request->get('collection');
        $activityID = $request->get('activity');

        $collection = $collectionRepository->find($collectionID);
        $activity = $activityRepository->find($activityID);

        // Test if activity is already in collection
        $a = $this->collectionContentsRepository->findBy([
            'collection' => $collectionID,
            'activity' => $activityID
        ]);

        if ($a) {
            return new JsonResponse([
                'error' => 'Activity already in collection'
            ], 409);
        }
        dd($a);

        if (!$a) {
            $collectionContents = new CollectionContents();
            $collectionContents->setActivity($activity);
            $collectionContents->setCollection($collection);
            $collectionContents->setPosition(1);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($collectionContents);
        $entityManager->flush();
        dd($collectionContents);

    }


}