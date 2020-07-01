<?php
declare(strict_types=1);
namespace App\Domain\Services;

use App\Domain\Entity\Walk;
use App\Domain\Repository\WalkRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

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
     * WalkServices constructor.
     * @param WalkRepositoryInterface $walkRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(WalkRepositoryInterface $walkRepository, EntityManagerInterface $entityManager)
    {
        $this->walkRepository = $walkRepository;
        $this->entityManager = $entityManager;
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
}