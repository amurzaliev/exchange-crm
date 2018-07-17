<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setEmail('admin@mail.ru')
            ->setPassword($this->userPasswordEncoder->encodePassword($user, '12345'))
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setUsername('admin')
        ->setEnabled('true');

        $manager->persist($user);
        $manager->flush();

        $user = new User();

        $user->setEmail('user@mail.ru')
            ->setPassword($this->userPasswordEncoder->encodePassword($user, '12345'))
            ->setRoles(['ROLE_USER'])
            ->setUsername('user')
            ->setEnabled('true');

        $manager->persist($user);

        $manager->flush();
    }
}
