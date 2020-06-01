<?php


namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function select2Find(): array
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT t.id, t.name FROM App\Entity\Tag t ORDER BY t.name DESC '
        );
        return $query->getResult();
    }
}