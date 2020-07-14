<?php
declare(strict_types=1);
namespace App\Domain\Services;

use App\Domain\Entity\Ride;
use App\Domain\Repository\RideRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

final class RideServices
{
    /**
     * @var RideRepository
     */
    private $rideRepository;
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
     * @param RideRepository $rideRepository
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     */
    public function __construct(RideRepository $rideRepository,
                                EntityManagerInterface $entityManager,
                                PaginatorInterface $paginator)
    {
        $this->rideRepository = $rideRepository;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    public function createRide(Ride $ride): Ride
    {
        $this->rideRepository->add($ride);

        $this->entityManager->flush();

        return $ride;
    }

    public function updateRide(Ride $ride): void
    {
        $this->rideRepository->add($ride);

        $this->entityManager->flush();

        return;
    }

    public function getRide($term): ?Ride
    {
        $slug_pattern = '/[a-zA-Z0-9\-_\/]+/';
        if (is_int($term)) {
            return $this->rideRepository->findById($term);
        } elseif (preg_match($slug_pattern, $term)) {
            return $this->rideRepository->findBySlug($term);
        }
    }

    /**
     * @param $term
     * @param $pageNumber
     * @return PaginationInterface
     */
    public function getPaginatedSearchResults($term, $pageNumber)
    {
        $queryBuilder = $this->rideRepository->getWithSearch($term);

        return $this->paginator->paginate (
            $queryBuilder, /* query NOT result */
            $pageNumber/*page number*/,
            10/*limit per page*/
        );
    }

    public function recentModifiedRide($max)
    {
        return $this->rideRepository->recentModifiedRide($max);
    }

    public function countRides(): int
    {
        return $this->rideRepository->size();
    }
}