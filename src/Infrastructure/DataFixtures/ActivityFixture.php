<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\DataTypes\Spatial\Types\Geography\LineString;
use App\Domain\DataTypes\Spatial\Types\Geography\Point;
use App\Domain\Entity\Tag;
use App\Domain\Entity\User;
use App\Domain\Entity\Walk;
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
            $walk->setDifficulty(mt_rand(1,3));
            $walk->setCreatedUser($this->getReference(User::class.'_0'));
            $walk->setModifiedUser($this->getReference(User::class.'_0'));
            $walk->setPoint(new Point(-1.058944, 51.281472));
            $walk->setRoute(new LineString([[-1.058944, 51.281472],[-1.058945, 51.281476],[-1.058955, 51.281476]]));

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
