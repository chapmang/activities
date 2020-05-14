<?php

namespace App\Service;

use App\Entity\Walk;
use App\Model\WalkModel;
use Doctrine\ORM\EntityManagerInterface;

class WalkFacade
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createWalk(WalkModel $walkModel)
    {
        $walk = new Walk();

        $walk = $this->hydrate($walk, $walkModel);

        $this->em->persist($walk);
        $this->em->flush();
        return $walk;
    }

    public function updateWalk(Walk $walk, WalkModel $walkModel)
    {
        $walk = $this->hydrate($walk, $walkModel);
        //dd($walk);
        $this->em->persist($walk);
        $this->em->flush();
        return;
    }

    private function hydrate(Walk $walk, WalkModel $walkModel): Walk
    {

        $hydrator = new WalkHydrator($walk, $walkModel);
        $walk = $hydrator->hydrate();
        return $walk;
    }
}