<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixture extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class, 1, function(User $user, $count){
            $user->setUsername('geoff');
        });

        $manager->flush();
    }
}
