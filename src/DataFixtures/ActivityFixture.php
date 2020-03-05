<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Activity;
use App\Entity\Walk;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ActivityFixture extends BaseFixture implements DependentFixtureInterface
{


    protected function loadData(ObjectManager $manager)
    {

        $this->createMany(Walk::class, 20, function(Walk $walk){
            $walk->setName('The Second Walk'.rand(0,1000));
            $walk->setShortDescription('This is the short description of this walk');
            $walk->setDescription($this->faker->paragraphs($nb = 3, $asText = true));
            $walk->setCreatedUser($this->getReference(User::class.'_0'));
            $walk->setModifiedUser($this->getReference(User::class.'_0'));

            $tags = $this->getRandomReferences(Tag::class, $this->faker->numberBetween(0, 5));
            foreach ($tags as $tag){
                $walk->addTag($tag);
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
