<?php
declare(strict_types=1);
namespace App\Domain\Repository;

use App\Domain\Entity\Activity;

Interface ActivityRepositoryInterface
{
    public function add(Activity $activity): void;

    public function remove(Activity $activity): void;

    public function findById(int $id): ?Activity;

    public function size(): int;

}