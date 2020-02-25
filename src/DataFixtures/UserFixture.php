<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {

        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class, 1, function(User $user, $count){
            $user->setEmail(sprintf('spacebar%d@example.com', $count));
            $user->setFirstName('Geoff');
            $user->setSurname('Chapman');
            $user->setPassword($this->userPasswordEncoder->encodePassword(
                $user,
                'test'));
        });

        $manager->flush();
    }
}
