<?php


namespace App\Service;


use App\Entity\Activity;

interface HydratorInterface
{
    public function hydrate();
}