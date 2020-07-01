<?php

namespace App\Domain\Entity;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="poi")
 * @ORM\Entity(repositoryClass="App\Domian\Repository\PoiRepository")
 */
class Poi extends Activity {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @return string
     * @Serializer\VirtualProperty()
     * @Groups({"activity"})
     */
    public function getType() {

        return $this::TYPE_POI;
    }
}