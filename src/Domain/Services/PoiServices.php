<?php
declare(strict_types=1);
namespace App\Domain\Services;

use App\Domain\Entity\Poi;
use App\Domain\Repository\PoiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

final class PoiServices
{
    /**
     * @var PoiRepository
     */
    private $poiRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * PoiServices constructor.
     * @param PoiRepository $poiRepository
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     */
    public function __construct(PoiRepository $poiRepository,
                                EntityManagerInterface $entityManager,
                                PaginatorInterface $paginator)
    {
        $this->poiRepository = $poiRepository;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    public function createPoi(Poi $poi): Poi
    {
        $this->poiRepository->add($poi);

        $this->entityManager->flush();

        return $poi;
    }

    public function updatePoi(Poi $poi): void
    {
        $this->poiRepository->add($poi);

        $this->entityManager->flush();

        return;
    }

    public function getPoi($term): ?Poi
    {
        $slug_pattern = '/[a-zA-Z0-9\-_\/]+/';
        if (is_int($term)) {
            return $this->poiRepository->findById($term);
        } elseif (preg_match($slug_pattern, $term)) {
            return $this->poiRepository->findBySlug($term);
        }
    }

    /**
     * @param $term
     * @param $pageNumber
     * @return PaginationInterface
     */
    public function getPaginatedSearchResults($term, $pageNumber)
    {
        $queryBuilder = $this->poiRepository->getWithSearch($term);

        return $this->paginator->paginate (
            $queryBuilder, /* query NOT result */
            $pageNumber/*page number*/,
            10/*limit per page*/
        );
    }

    public function recentModifiedPoi($max)
    {
        return $this->poiRepository->recentModifiedPoi($max);
    }

    public function countPois(): int
    {
        return $this->poiRepository->size();
    }
}