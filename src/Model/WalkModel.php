<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class WalkModel extends ActivityModel
{
    /**
     * @var string
     */
    protected $location;

    /**
     * @Assert\Type(type="float")
     * @var float
     */
    protected $distance;

    /**
     * @var float
     */
    protected $distanceMiles;

    /**
     * @var integer
     * @Assert\Type(type="integer")
     */
    protected $minimumTimeHours;

    /**
     * @var integer
     * @Assert\Type(type="integer")
     */
    protected $minimumTimeMinutes;

    /**
     * @var integer
     * @Assert\Type(type="integer")
     */
    protected $ascent;

    /**
     * @var float
     */
    protected $ascentFeet;

    /**
     * @var integer
     * @Assert\Choice({0,1,2,3})
     */
    protected $gradient;

    /**
     * @var integer
     * @Assert\Choice({1,2,3})
     */
    protected $difficulty;

    /**
     * @var string
     */
    protected $paths;

    /**
     * @var string
     */
    protected $landscape;

    /**
     * @var string
     */
    protected $suggestedMap;

    /**
     * @var string
     * @Assert\Regex("/^([STNHOstnho][A-Za-z]\s?)(\d{5}\s?\d{5}|\d{4}\s?\d{4}|\d{3}\s?\d{3}|\d{2}\s?\d{2}|\d{1}\s?\d{1})$/", message="This value is not a valid grid reference")

     */
    protected $finishGridRef;


    /**
     * @var string
     */
    protected $dogFriendliness;

    /**
     * @var string
     */
    protected $parking;

    /**
     * @var string
     */
    protected $publicToilet;

    /**
     * @var string
     */
    protected $notes;

    /**
     * @var string
     */
    protected $whatToLookOutFor;

    /**
     * @var string
     */
    protected $whereToEatAndDrink;

    /**
     * @var string
     */
    protected $whileYouAreThere;

    /**
     * @var string
     */
    protected $extension;

    /**
     * @return string
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    /**
     * @return float
     */
    public function getDistance(): ?float
    {
        return $this->distance;
    }

    /**
     * @param float $distance
     */
    public function setDistance(?float $distance): void
    {
        $this->distance = $distance;
    }

    /**
     * @return float
     */
    public function getDistanceMiles(): ?float
    {
        return $this->distanceMiles;
    }

    /**
     * @param float $distanceMiles
     */
    public function setDistanceMiles(?float $distanceMiles): void
    {
        $this->distanceMiles = $distanceMiles;
    }

    /**
     * @return int
     */
    public function getMinimumTimeHours(): ?int
    {
        return $this->minimumTimeHours;
    }

    /**
     * @param int $minimumTimeHours
     */
    public function setMinimumTimeHours(?int $minimumTimeHours): void
    {
        $this->minimumTimeHours = $minimumTimeHours;
    }

    /**
     * @return int
     */
    public function getMinimumTimeMinutes(): ?int
    {
        return $this->minimumTimeMinutes;
    }

    /**
     * @param int $minimumTimeMinutes
     */
    public function setMinimumTimeMinutes(?int $minimumTimeMinutes): void
    {
        $this->minimumTimeMinutes = $minimumTimeMinutes;
    }

    /**
     * @return int
     */
    public function getAscent(): ?int
    {
        return $this->ascent;
    }

    /**
     * @param int $ascent
     */
    public function setAscent(?int $ascent): void
    {
        $this->ascent = $ascent;
    }

    /**
     * @return float
     */
    public function getAscentFeet(): ?float
    {
        return $this->ascentFeet;
    }

    /**
     * @param float $ascentFeet
     */
    public function setAscentFeet(?float $ascentFeet): void
    {
        $this->ascentFeet = $ascentFeet;
    }

    /**
     * @return int
     */
    public function getGradient(): ?int
    {
        return $this->gradient;
    }

    /**
     * @param int $gradient
     */
    public function setGradient(?int $gradient): void
    {
        $this->gradient = $gradient;
    }

    /**
     * @return int
     */
    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    /**
     * @param int $difficulty
     */
    public function setDifficulty(?int $difficulty): void
    {
        $this->difficulty = $difficulty;
    }

    /**
     * @return string
     */
    public function getPaths(): ?string
    {
        return $this->paths;
    }

    /**
     * @param string $paths
     */
    public function setPaths(?string $paths): void
    {
        $this->paths = $paths;
    }

    /**
     * @return string
     */
    public function getLandscape(): ?string
    {
        return $this->landscape;
    }

    /**
     * @param string $landscape
     */
    public function setLandscape(?string $landscape): void
    {
        $this->landscape = $landscape;
    }

    /**
     * @return string
     */
    public function getSuggestedMap(): ?string
    {
        return $this->suggestedMap;
    }

    /**
     * @param string $suggestedMap
     */
    public function setSuggestedMap(?string $suggestedMap): void
    {
        $this->suggestedMap = $suggestedMap;
    }

    /**
     * @return string
     */
    public function getFinishGridRef(): ?string
    {
        return $this->finishGridRef;
    }

    /**
     * @param string $finishGridRef
     */
    public function setFinishGridRef(?string $finishGridRef): void
    {
        $this->finishGridRef = $finishGridRef;
    }

    /**
     * @return string
     */
    public function getDogFriendliness(): ?string
    {
        return $this->dogFriendliness;
    }

    /**
     * @param string $dogFriendliness
     */
    public function setDogFriendliness(?string $dogFriendliness): void
    {
        $this->dogFriendliness = $dogFriendliness;
    }

    /**
     * @return string
     */
    public function getParking(): ?string
    {
        return $this->parking;
    }

    /**
     * @param string $parking
     */
    public function setParking(?string $parking): void
    {
        $this->parking = $parking;
    }

    /**
     * @return string
     */
    public function getPublicToilet(): ?string
    {
        return $this->publicToilet;
    }

    /**
     * @param string $publicToilet
     */
    public function setPublicToilet(?string $publicToilet): void
    {
        $this->publicToilet = $publicToilet;
    }

    /**
     * @return string
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     */
    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }

    /**
     * @return string
     */
    public function getWhatToLookOutFor(): ?string
    {
        return $this->whatToLookOutFor;
    }

    /**
     * @param string $whatToLookOutFor
     */
    public function setWhatToLookOutFor(?string $whatToLookOutFor): void
    {
        $this->whatToLookOutFor = $whatToLookOutFor;
    }

    /**
     * @return string
     */
    public function getWhereToEatAndDrink(): ?string
    {
        return $this->whereToEatAndDrink;
    }

    /**
     * @param string $whereToEatAndDrink
     */
    public function setWhereToEatAndDrink(?string $whereToEatAndDrink): void
    {
        $this->whereToEatAndDrink = $whereToEatAndDrink;
    }

    /**
     * @return string
     */
    public function getWhileYouAreThere(): ?string
    {
        return $this->whileYouAreThere;
    }

    /**
     * @param string $whileYouAreThere
     */
    public function setWhileYouAreThere(?string $whileYouAreThere): void
    {
        $this->whileYouAreThere = $whileYouAreThere;
    }

    /**
     * @return string
     */
    public function getExtension(): ?string
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     */
    public function setExtension(?string $extension): void
    {
        $this->extension = $extension;
    }

}