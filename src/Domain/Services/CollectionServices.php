<?php
declare(strict_types=1);
namespace App\Domain\Services;


use App\Domain\Entity\Collection;
use App\Domain\Repository\CollectionRepository;
use Doctrine\ORM\EntityManagerInterface;

final class CollectionServices
{
    /**
     * @var CollectionRepository
     */
    private $collectionRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(CollectionRepository $collectionRepository, EntityManagerInterface $entityManager)
    {
        $this->collectionRepository = $collectionRepository;
        $this->entityManager = $entityManager;
    }

    public function createCollection(Collection $collection): Collection
    {
        $this->collectionRepository->add($collection);

        $this->entityManager->flush();

        return $collection;
    }

    public function updateCollection(Collection $collection): void
    {
        $this->collectionRepository->add($collection);

        $this->entityManager->flush();

        return;
    }

    public function getCollection($term): ?Collection
    {
        $slug_pattern = '/[a-zA-Z0-9\-_\/]+/';
        if (is_int($term)) {
            return $this->collectionRepository->findById($term);
        } elseif (preg_match($slug_pattern, $term)) {
            return $this->collectionRepository->findBySlug($term);
        }
    }

    public function getActivitiesNotInCollection($searchCollection, $searchTerm)
    {
        return $this->collectionRepository->searchActivitiesNotInCollection($searchCollection, $searchTerm);
    }

}