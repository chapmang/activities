<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Activity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ActivityFixture extends BaseFixture implements DependentFixtureInterface
{


    protected function loadData(ObjectManager $manager)
    {

        $this->createMany(Activity::class, 20, function(Activity $activity){
            $activity->setName('The Second Walk'.rand(0,1000));
            $activity->setDescription('This Walk even has a description');
            $activity->setCreatedUser($this->getReference(User::class.'_0'));
            $activity->setModifiedUser($this->getReference(User::class.'_0'));

            $tags = $this->getRandomReferences(Tag::class, $this->faker->numberBetween(0, 5));
            $arrayofTags = [];
            for($i = 0; $i < count($tags); $i++) {
                $id = $tags[$i]->getId();
                if ($i == 1) {
                    $arrayofTags[]=$id;
                } else if (in_array($id, $arrayofTags)) {
                    continue;
                } else {
                    $activity->addTag($tags[$i]);
                }
            }
        });
        $manager->flush();

    }

    public function getDependencies() {
        return [
            UserFixture::class,
            TagFixture::class
        ];
    }
}