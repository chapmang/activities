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
        // Base Activity
        $walk->setName($walkModel->getName());
        $walk->setStartGridRef($walkModel->getStartGridRef());
        $walk->setShortDescription($walkModel->getShortDescription());
        $walk->setDescription($walkModel->getDescription());

        // Walk
        $walk->setLocation($walkModel->getLocation());
        $walk->setDistance($walkModel->getDistance());
        $walk->setMinimumTimeHours($walkModel->getMinimumTimeHours());
        $walk->setMinimumTimeMinutes($walkModel->getMinimumTimeMinutes());
        $walk->setAscent($walkModel->getAscent());
        $walk->setGradient($walkModel->getGradient());
        $walk->setDifficulty($walkModel->getDifficulty());
        $walk->setPaths($walkModel->getPaths());
        $walk->setLandscape($walkModel->getLandscape());
        $walk->setFinishGridRef($walkModel->getFinishGridRef());
        $walk->setDogFriendliness($walkModel->getDogFriendliness());
        $walk->setParking($walkModel->getParking());
        $walk->setPublicToilet($walkModel->getPublicToilet());
        $walk->setNotes($walkModel->getNotes());
        $walk->setWhatToLookOutFor($walkModel->getWhatToLookOutFor());
        $walk->setWhereToEatAndDrink($walkModel->getWhereToEatAndDrink());
        $walk->setWhileYouAreThere($walkModel->getWhileYouAreThere());
        $walk->setExtension($walkModel->getExtension());
        $walk->setSuggestedMap($walkModel->getSuggestedMap());

        // Related
        $walk->setMapRoyalty();
        $walk->setTags();

        $this->em->persist($walk);
        $this->em->flush();
        return $walk;
    }

    public function updateWalk(Walk $walk, WalkModel $walkModel)
    {
        // Base Activity
        $walk->setName($walkModel->getName());
        $walk->setStartGridRef($walkModel->getStartGridRef());
        $walk->setShortDescription($walkModel->getShortDescription());
        $walk->setDescription($walkModel->getDescription());

        // Walk
        $walk->setLocation($walkModel->getLocation());
        $walk->setDistance($walkModel->getDistance());
        $walk->setMinimumTimeHours($walkModel->getMinimumTimeHours());
        $walk->setMinimumTimeMinutes($walkModel->getMinimumTimeMinutes());
        $walk->setAscent($walkModel->getAscent());
        $walk->setGradient($walkModel->getGradient());
        $walk->setDifficulty($walkModel->getDifficulty());
        $walk->setPaths($walkModel->getPaths());
        $walk->setLandscape($walkModel->getLandscape());
        $walk->setFinishGridRef($walkModel->getFinishGridRef());
        $walk->setDogFriendliness($walkModel->getDogFriendliness());
        $walk->setParking($walkModel->getParking());
        $walk->setPublicToilet($walkModel->getPublicToilet());
        $walk->setNotes($walkModel->getNotes());
        $walk->setWhatToLookOutFor($walkModel->getWhatToLookOutFor());
        $walk->setWhereToEatAndDrink($walkModel->getWhereToEatAndDrink());
        $walk->setWhileYouAreThere($walkModel->getWhileYouAreThere());
        $walk->setExtension($walkModel->getExtension());
        $walk->setSuggestedMap($walkModel->getSuggestedMap());


        $this->em->persist($walk);
        $this->em->flush();
        return;
    }
}