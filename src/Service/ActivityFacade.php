<?php

namespace App\Service;

use App\Domain\Entity\Activity;
use App\Model\ActivityModel;
use Doctrine\ORM\EntityManagerInterface;

class ActivityFacade
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createWalk(ActivityModel $activityModel)
    {
        $activity = new Activity();

        $activity = $this->hydrate($activity, $activityModel);

        $this->em->persist($activity);
        $this->em->flush();
        return $activity;
    }

    public function updateWalk(Activity $activity, ActivityModel $activityModel)
    {
        $activity = $this->hydrate($activity, $activityModel);

        $this->em->persist($activity);
        $this->em->flush();
        return;
    }

    private function hydrate(Activity $activity, ActivityModel $activityModel): Activity
    {

        $hydrator = new ActivityHydrator($activity, $activityModel);
        $activity = $hydrator->hydrate();
        return $activity;
    }
}