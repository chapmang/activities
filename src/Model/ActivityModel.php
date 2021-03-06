<?php


namespace App\Model;

use App\Domain\Entity\Tag;
use App\Application\Validator\UniqueActivity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ActivityFormModel
 * @package App\Form\Model
 * @UniqueActivity()
 */
class ActivityModel
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank(message = "Name is a required element of all activities")
     *
     */
    protected $name;

    /**
     * @var string
     * @Assert\Regex("/^([STNHOstnho][A-Za-z]\s?)(\d{5}\s?\d{5}|\d{4}\s?\d{4}|\d{3}\s?\d{3}|\d{2}\s?\d{2}|\d{1}\s?\d{1})$/", message="This value is not a valid grid reference")
     */
    protected $startGridRef;

    /**
     * @var string
     * @Assert\Regex("/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/", message="This value is not a valid Latitude")
     */
    protected $latitude;

    /**
     * @var string
     * @Assert\Regex("/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/", message="This value is not a valid Longitude")
     */
    protected $longitude;

    /**
     * @var string
     */
    protected $shortDescription;

    /**
     * @var string
     */
    protected $description;

    /**
    * @var integer
    */
    protected $status;

    /**
     * @var boolean
     */
    protected $onlineFriendly;

    public $tags;

    public $directions;

    protected $type;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }


    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getStartGridRef(): ?string
    {
        return $this->startGridRef;
    }

    /**
     * @param string $startGridRef
     */
    public function setStartGridRef(?string $startGridRef): void
    {
        $this->startGridRef = $startGridRef;
    }

    /**
     * @return string
     */
    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    /**
     * @param string $shortDescription
     */
    public function setShortDescription(?string $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }


    /**
     * @return string
     */
    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    /**
     * @param string $latitude
     */
    public function setLatitude(?string $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude(?string $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function isOnlineFriendly(): bool
    {
        return $this->onlineFriendly;
    }

    /**
     * @param bool $onlineFriendly
     */
    public function setOnlineFriendly(bool $onlineFriendly): void
    {
        $this->onlineFriendly = $onlineFriendly;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags): void
    {
        $this->tags = $tags;
    }

    public function hasTag($tag): bool
    {
        return $this->tags->contains($tag);
    }


    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }


}