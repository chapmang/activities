<?php


namespace App\Model\ModelFactory;


use App\Entity\Activity;
use App\Model\ActivityModel;

final class ActivityModelFactory implements ModelFactoryInterface
{
    public static function createActivity(Activity $activity = null): ActivityModel
    {
        $activityModel = new ActivityModel();
        if (!is_null($activity)) {
            // Base Activity
            $activityModel->setId($activity->getId());
            $activityModel->setName($activity->getName());
            $activityModel->setStartGridRef($activity->getStartGridRef());
            $activityModel->setLatitude($activity->getLatitude());
            $activityModel->setLongitude($activity->getLongitude());
            $activityModel->setShortDescription($activity->getShortDescription());
            $activityModel->setDescription($activity->getDescription());
            $activityModel->setStatus($activity->getStatus());
            $activityModel->setType($activity->getActivityType());
        };

        return $activityModel;
    }

}