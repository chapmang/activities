<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="app_user")
 * @UniqueEntity(fields={"username"}, message = "Username already exists")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @Groups({"activity"})
     */
    private $id;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=190, unique=true)
     * @Groups({"activity"})
     */
    private $username;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getUsername() {

        return $this->username;
    }

    public function setUsername($username) {

        $this->username = $username;
    }



}