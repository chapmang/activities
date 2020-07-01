<?php
declare(strict_types=1);
namespace App\Domain\Repository;

use App\Domain\Entity\Walk;

interface WalkRepositoryInterface
{
    public function add(Walk $walk): void;

    public function remove(Walk $walk): void;

    public function findById(int $id): ?Walk;

    public function size(): int;

}