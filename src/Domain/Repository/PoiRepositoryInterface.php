<?php
declare(strict_types=1);
namespace App\Domain\Repository;

use App\Domain\Entity\Poi;

interface PoiRepositoryInterface
{
    public function add(Poi $walk): void;

    public function remove(Poi $walk): void;

    public function findById(int $id): ?Poi;

    public function size(): int;

}