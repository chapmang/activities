<?php


namespace App\Service;


use App\Domain\Entity\Collection;
use Doctrine\ORM\EntityManagerInterface;

class CollectionFacade
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createCollection(Collection $collection)
    {
        $this->em->persist($collection);
        $this->em->flush();
        return;
    }

    public function updateCollection(Collection $collection)
    {
        $this->em->persist($collection);
        $this->em->flush();
        return;

    }
}