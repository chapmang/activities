<?php
declare(strict_types=1);
namespace App\Presentation\Web\Pub\Controller;

use App\Domain\Entity\CollectionContents;
use App\Domain\Repository\ActivityRepository;
use App\Domain\Repository\CollectionContentsRepository;
use App\Domain\Repository\CollectionRepository;
use App\Domain\Services\CollectionContentsServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CollectionContentsController extends AbstractController
{

    private $collectionContentsService;

    public function __construct(CollectionContentsServices $collectionContentsServices)
    {
        $this->collectionContentsService = $collectionContentsServices;
    }

    /**
     * @Route("/collection/addactivity", name="collection_add_activity", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function addActivity(Request $request)
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $collection_id = (int)$data['collection'];
        $activity_id = (int)$data['activity'];

        // Test if activity is already in collection
        $result = $this->collectionContentsService
            ->activityInCollectionTest($collection_id, $activity_id);

        if ($result == true) {
            return new JsonResponse([
                'error' => 'Activity already in collection'
            ], 409);
        }

        if ($result == false) {
             $collectionContents = $this->collectionContentsService->addActivityToCollection($activity_id, $collection_id);
        }

        $activity = $collectionContents->getActivity();

        return new JsonResponse([
            'id' => $activity->getId(),
            'name' => $activity->getName(),
            'activityType' => $activity->getActivityType(),
        ], 200);

    }


}