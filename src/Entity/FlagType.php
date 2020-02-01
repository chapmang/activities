<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * App\Entity\FlagType
 * @author Geoff Chapman <geoff.chapman@mac.com>
 * @version 1.0
 *
 * @ORM\Table(name="flag_type")
 * @ORM\Entity(repositoryClass="App\Repository\FlagTypeRepository")
 * @UniqueEntity(
 *     fields = "name",
 *     message = "The flag '{{ value }}' already exists"
 * )
 *
 */
class FlagType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $name;

    public function getId() {

        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName() {

        return $this->name;
    }

    /**
     * @param mixed $name
     * @return FlagType
     */
    public function setName($name) {

        $this->name = $name;
        return $this;
    }


}
