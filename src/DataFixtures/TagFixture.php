<?php


namespace App\DataFixtures;


use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TagFixture extends BaseFixture implements DependentFixtureInterface
{

    protected function loadData(ObjectManager $manager) {

        $this->createMany(Tag::class, 10, function(Tag $tag) {
            $tag->setName($this->faker->unique(true)->realText(15));
            $tag->setCreatedUser($this->getReference(User::class.'_0'));
            $tag->setModifiedUser($this->getReference(User::class.'_0'));
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class
        ];
    }
}