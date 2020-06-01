<?php

namespace App\Model\ModelFactory;

use App\Entity\Activity;
use App\Entity\Walk;
use App\Model\WalkModel;

final class WalkModelFactory implements ModelFactoryInterface
{
    public static function createActivity(Walk $walk = null): WalkModel
    {

        $walkModel = new WalkModel();
        if (!is_null($walk)){
            // Base Activity
            $walkModel->setId($walk->getId());
            $walkModel->setName($walk->getName());
            $walkModel->setStartGridRef($walk->getStartGridRef());
            $walkModel->setShortDescription($walk->getShortDescription());
            $walkModel->setDescription($walk->getDescription());
            $walkModel->setStatus($walk->getStatus());

            // Walk
            $walkModel->setLocation($walk->getLocation());
            $walkModel->setDistance($walk->getDistance());
            $walkModel->setMinimumTimeHours($walk->getMinimumTimeHours());
            $walkModel->setMinimumTimeMinutes($walk->getMinimumTimeMinutes());
            $walkModel->setAscent($walk->getAscent());
            $walkModel->setGradient($walk->getGradient());
            $walkModel->setDifficulty($walk->getDifficulty());
            $walkModel->setPaths($walk->getPaths());
            $walkModel->setLandscape($walk->getLandscape());
            $walkModel->setFinishGridRef($walk->getFinishGridRef());
            $walkModel->setDogFriendliness($walk->getDogFriendliness());
            $walkModel->setParking($walk->getParking());
            $walkModel->setPublicToilet($walk->getPublicToilet());
            $walkModel->setNotes($walk->getNotes());
            $walkModel->setWhatToLookOutFor($walk->getWhatToLookOutFor());
            $walkModel->setWhereToEatAndDrink($walk->getWhereToEatAndDrink());
            $walkModel->setWhileYouAreThere($walk->getWhileYouAreThere());
            $walkModel->setExtension($walk->getExtension());
            $walkModel->setSuggestedMap($walk->getSuggestedMap());

            // Related
//        $walkModel->setMapRoyalty();
            $walkModel->tags = $walk->getTags();
            $walkModel->directions = $walk->getDirections();
            $walkModel->setWhereToEatAndDrink($walk->getWhereToEatAndDrink());
            $walkModel->setWhatToLookOutFor($walk->getWhatToLookOutFor());
            $walkModel->setWhileYouAreThere($walk->getWhileYouAreThere());

        }

        return $walkModel;
    }
}