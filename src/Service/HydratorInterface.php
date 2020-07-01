<?php


namespace App\Service;


use App\Domain\Entity\Activity;

interface HydratorInterface
{
    public function hydrate();
}