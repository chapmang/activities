<?php


namespace App\Model;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ActivityFormModel
 * @package App\Form\Model
 */
class ActivityModel
{
    /**
     * @var string
     * @Assert\NotBlank(message = "Name is a required element of all activities")
     */
    protected $name;

    /**
     * @var string
     * @Assert\Regex("/^([STNHOstnho][A-Za-z]\s?)(\d{5}\s?\d{5}|\d{4}\s?\d{4}|\d{3}\s?\d{3}|\d{2}\s?\d{2}|\d{1}\s?\d{1})$/", message="This value is not a valid grid reference")
     */
    protected $startGridRef;

    /**
     * @var string
     */
    protected $shortDescription;

    /**
     * @var string
     */
    protected $description;


    protected $tagsText;

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
     * @return mixed
     */
    public function getTagsText()
    {
        return $this->tagsText;
    }

    /**
     * @param mixed $tagsText
     */
    public function setTagsText($tagsText): void
    {
        $this->tagsText = $tagsText;
    }


}