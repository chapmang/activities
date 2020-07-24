<?php
declare(strict_types=1);
namespace App\Domain\Repository;

use App\Domain\Entity\CollectionContents;

interface CollectionContentsRepositoryInterface
{
    public function add(CollectionContents $collection): void;

    public function remove(CollectionContents $collection): void;

    public function findById(int $id): ?CollectionContents;

    public function size(): int;

}