<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class, 1, function(User $user) {
            $user->setUsername('geoff.chapman');
            $user->setFirstName('Geoff');
            $user->setSurname('Chapman');
            $user->setEmail('geoff.chapman@aamediagroup.co.uk');
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'test'));
            $user->setRoles(['ROLE_ADMIN']);
        });

        $manager->flush();
    }
}
