<?php
declare(strict_types=1);
namespace App\Domain\Services;

use App\Domain\Entity\Drive;
use App\Domain\Repository\DriveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

final class DriveServices
{
    /**
     * @var DriveRepository
     */
    private $driveRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * DriveServices constructor.
     * @param DriveRepository $driveRepository
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     */
    public function __construct(DriveRepository $driveRepository,
                                EntityManagerInterface $entityManager,
                                PaginatorInterface $paginator)
    {
        $this->driveRepository = $driveRepository;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    public function createDrive(Drive $drive): Drive
    {
        $this->driveRepository->add($drive);

        $this->entityManager->flush();

        return $drive;
    }

    public function updatePoi(Drive $drive): void
    {
        $this->driveRepository->add($drive);

        $this->entityManager->flush();

        return;
    }

    public function getPoi($term): ?Drive
    {
        $slug_pattern = '/[a-zA-Z0-9\-_\/]+/';
        if (is_int($term)) {
            return $this->driveRepository->findById($term);
        } elseif (preg_match($slug_pattern, $term)) {
            return $this->driveRepository->findBySlug($term);
        }
    }

    /**
     * @param $term
     * @param $pageNumber
     * @return PaginationInterface
     */
    public function getPaginatedSearchResults($term, $pageNumber)
    {
        $queryBuilder = $this->driveRepository->getWithSearch($term);

        return $this->paginator->paginate (
            $queryBuilder, /* query NOT result */
            $pageNumber/*page number*/,
            10/*limit per page*/
        );
    }

    public function recentModifiedDrive($max)
    {
        return $this->driveRepository->recentModifiedDrive($max);
    }

    public function countDrives(): int
    {
        return $this->driveRepository->size();
    }
}