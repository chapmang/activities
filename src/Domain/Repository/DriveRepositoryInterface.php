<?php
declare(strict_types=1);
namespace App\Domain\Repository;

use App\Domain\Entity\Drive;

interface DriveRepositoryInterface
{
    public function add(Drive $drive): void;

    public function remove(Drive $drive): void;

    public function findById(int $id): ?Drive;

    public function size(): int;

}