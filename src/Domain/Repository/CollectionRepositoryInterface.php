<?php
declare(strict_types=1);
namespace App\Domain\Repository;

use App\Domain\Entity\Collection;

interface CollectionRepositoryInterface
{
    public function add(Collection $collection): void;

    public function remove(Collection $collection): void;

    public function findById(int $id): ?Collection;

    public function size(): int;

}