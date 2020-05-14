<?php

namespace App\Service;

use App\Entity\Walk;
use App\Model\WalkModel;

class WalkHydrator implements HydratorInterface
{
    private $walk;

    private $walkModel;

    public function __construct(Walk $walk, WalkModel $walkModel)
    {
        $this->walk = $walk;
        $this->walkModel = $walkModel;
    }

    public function hydrate()
    {
        $this->walk->setName($this->walkModel->getName());
        $this->walk->setStartGridRef($this->walkModel->getStartGridRef());
        $this->walk->setShortDescription($this->walkModel->getShortDescription());
        $this->walk->setLatitude($this->walkModel->getLatitude());
        $this->walk->setLongitude($this->walkModel->getLongitude());
        $this->walk->setDescription($this->walkModel->getDescription());
        $this->walk->setStatus($this->walkModel->getStatus());

        // Walk
        $this->walk->setLocation($this->walkModel->getLocation());
        $this->walk->setDistance($this->walkModel->getDistance());
        $this->walk->setMinimumTimeHours($this->walkModel->getMinimumTimeHours());
        $this->walk->setMinimumTimeMinutes($this->walkModel->getMinimumTimeMinutes());
        $this->walk->setAscent($this->walkModel->getAscent());
        $this->walk->setGradient($this->walkModel->getGradient());
        $this->walk->setDifficulty($this->walkModel->getDifficulty());
        $this->walk->setPaths($this->walkModel->getPaths());
        $this->walk->setLandscape($this->walkModel->getLandscape());
        $this->walk->setFinishGridRef($this->walkModel->getFinishGridRef());
        $this->walk->setDogFriendliness($this->walkModel->getDogFriendliness());
        $this->walk->setParking($this->walkModel->getParking());
        $this->walk->setPublicToilet($this->walkModel->getPublicToilet());
        $this->walk->setNotes($this->walkModel->getNotes());
        $this->walk->setWhatToLookOutFor($this->walkModel->getWhatToLookOutFor());
        $this->walk->setWhereToEatAndDrink($this->walkModel->getWhereToEatAndDrink());
        $this->walk->setWhileYouAreThere($this->walkModel->getWhileYouAreThere());
        $this->walk->setExtension($this->walkModel->getExtension());
        $this->walk->setSuggestedMap($this->walkModel->getSuggestedMap());

        foreach ($this->walkModel->directions as $dir)  {

            $this->walk->addDirection($dir);
        }
        $this->walk->setWhereToEatAndDrink($this->walkModel->getWhereToEatAndDrink());
        $this->walk->setWhatToLookOutFor($this->walkModel->getWhatToLookOutFor());
        $this->walk->setWhileYouAreThere($this->walkModel->getWhileYouAreThere());
        $this->walk->setTagsText($this->walkModel->getTagsText());


        return $this->walk;
    }
}