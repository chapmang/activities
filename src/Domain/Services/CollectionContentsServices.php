<?php
declare(strict_types=1);
namespace App\Domain\Services;

use App\Domain\Entity\Collection;
use App\Domain\Entity\CollectionContents;
use App\Domain\Repository\ActivityRepository;
use App\Domain\Repository\CollectionContentsRepository;
use App\Domain\Repository\CollectionRepository;
use Doctrine\ORM\EntityManagerInterface;

class CollectionContentsServices
{

    /**
     * @var CollectionContentsRepository
     */
    private $collectionContentsRepository;
    /**
     * @var CollectionRepository
     */
    private $collectionRepository;
    /**
     * @var ActivityRepository
     */
    private $activityRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(CollectionContentsRepository $collectionContentsRepository,
                                CollectionRepository $collectionRepository,
                                ActivityRepository $activityRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->collectionContentsRepository = $collectionContentsRepository;
        $this->collectionRepository = $collectionRepository;
        $this->activityRepository = $activityRepository;
        $this->entityManager = $entityManager;
    }

    public function createCollectionContents(CollectionContents $collectionContents): CollectionContents
    {
        $this->collectionContentsRepository->add($collectionContents);

        $this->entityManager->flush();

        return $collectionContents;
    }

    public function updateCollection(CollectionContents $collectionContents): void
    {
        $this->collectionContentsRepository->add($collectionContents);

        $this->entityManager->flush();

        return;
    }

    public function getCollection($term): ?CollectionContents
    {
        $slug_pattern = '/[a-zA-Z0-9\-_\/]+/';
        if (is_int($term)) {
            return $this->collectionContentsRepository->findById($term);
        } elseif (preg_match($slug_pattern, $term)) {
            return $this->collectionContentsRepository->findBySlug($term);
        }
    }

    /**
     * @param int $collection_id
     * @param int $activity_id
     * @return bool
     */
    public function activityInCollectionTest(int $collection_id, int $activity_id)
    {
        $result = $this->collectionContentsRepository->findBy([
            'collection' => $collection_id,
            'activity' => $activity_id
        ]);

        // Is the activity in the collection
        return !empty($result);
    }

    public function addActivityToCollection(int $activity_id, int $collection_id): CollectionContents
    {
        $activity = $this->activityRepository->findById($activity_id);
        $collection = $this->collectionRepository->findById($collection_id);
        $collectionContents = new CollectionContents();
        $collectionContents->setActivity($activity);
        $collectionContents->setCollection($collection);

        $maxPosition = $this->collectionContentsRepository->getMaxPosition($collection_id);
        $collectionContents->setPosition($maxPosition+1);

        $this->updateCollection($collectionContents);

        return $collectionContents;
    }


}