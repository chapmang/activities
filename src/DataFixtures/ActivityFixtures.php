<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Activity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ActivityFixtures extends BaseFixtures
{
    private $user;

    protected function loadData(ObjectManager $manager)
    {

        $this->user = new User();
        $this->user->setUsername('geoff');
        $manager->persist($this->user);
        $this->createMany(Activity::class, 20, function(Activity $activity, $count){
            $activity->setName('The Second Walk'.rand(0,1000));
            $activity->setDescription('This Walk even has a description');
            $activity->setCreatedUser($this->user);
            $activity->setModifiedUser($this->user);

        });
        $manager->flush();

    }
}
