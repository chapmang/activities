<?php
declare(strict_types=1);
namespace App\Domain\Repository;

use App\Domain\Entity\Ride;

interface RideRepositoryInterface
{
    public function add(Ride $ride): void;

    public function remove(Ride $ride): void;

    public function findById(int $id): ?Ride;

    public function size(): int;

}