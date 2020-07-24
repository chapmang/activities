<?php
declare(strict_types=1);
namespace App\Domain\Services;

use App\Domain\Entity\Walk;
use App\Domain\Repository\WalkRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

final class WalkServices
{
    /**
     * @var WalkRepositoryInterface
     */
    private $walkRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * WalkServices constructor.
     * @param WalkRepositoryInterface $walkRepository
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     */
    public function __construct(WalkRepositoryInterface $walkRepository,
                                EntityManagerInterface $entityManager,
                                PaginatorInterface $paginator)
    {
        $this->walkRepository = $walkRepository;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    public function createWalk(Walk $walk): Walk
    {
        $this->walkRepository->add($walk);

        $this->entityManager->flush();

        return $walk;
    }

    public function updateWalk(Walk $walk): void
    {
        // Prevent against any unfilled direction text areas
        foreach ($walk->getDirections() as $dir) {
            if (is_null($dir->getDirection())) {
                $walk->removeDirection($dir);
            }
        }

        $this->walkRepository->add($walk);

        $this->entityManager->flush();

        return;
    }

    public function getWalk($term): ?Walk
    {
        $slug_pattern = '/[a-zA-Z0-9\-_\/]+/';
        if (is_int($term)) {
            return $this->walkRepository->findById($term);
        } elseif (preg_match($slug_pattern, $term)) {
            return $this->walkRepository->findBySlug($term);
        }
    }

    /**
     * @param array $queryData
     * @param int $pageNumber
     * @return PaginationInterface
     */
    public function getPaginatedSearchResults(?array $queryData, int $pageNumber)
    {
        $queryTerm = ($queryData ? $queryData['string'] : null);
        $tags = ($queryData ? $queryData['tags'] : null);

        $queryBuilder = $this->walkRepository->findAllByNameAndTagsQueryBuilder($queryTerm, $tags);

        return $this->paginator->paginate (
            $queryBuilder, /* query NOT result */
            $pageNumber/*page number*/,
            10/*limit per page*/
        );
    }

    public function recentModifiedWalk($max)
    {
        return $this->walkRepository->recentModifiedWalk($max);
    }

    public function countWalks(): int
    {
        return $this->walkRepository->size();
    }
}