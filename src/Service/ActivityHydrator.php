<?php

namespace App\Service;

use App\Entity\Activity;
use App\Model\ActivityModel;


class ActivityHydrator implements HydratorInterface
{
    private $activity;

    private $activityModel;

    public function __construct(Activity $activity, ActivityModel $activityModel)
    {
        $this->activity = $activity;
        $this->activityModel = $activityModel;
    }

    public function hydrate()
    {
        $this->activity->setName($this->activityModel->getName());
        $this->activity->setStartGridRef($this->activityModel->getStartGridRef());
        $this->activity->setLatitude($this->activityModel->getLatitude());
        $this->activity->setLongitude($this->activityModel->getLongitude());
        $this->activity->setShortDescription($this->activityModel->getShortDescription());
        $this->activity->setDescription($this->activityModel->getDescription());
        $this->activity->setStatus($this->activityModel->getStatus());

        return $this->activity;
    }
}