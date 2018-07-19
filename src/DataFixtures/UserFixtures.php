<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    public const ADMIN = 'admin';
    public const USER = 'user';
    public const OWNER = 'owner';

    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();

        $admin->setEmail('admin@mail.ru')
            ->setPassword($this->userPasswordEncoder->encodePassword($user, '12345'))
            ->setRoles(['ROLE_ADMIN'])
            ->setEnabled('true');
            $manager->persist($admin);
            $manager->flush();

        $this->addReference(self::ADMIN, $admin);

        $user = new User();
        $user->setEmail('user@mail.ru')
             ->setPassword($this->userPasswordEncoder->encodePassword($user, '12345'))
             ->setRoles(['ROLE_USER'])
             ->setEnabled('true');
        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::USER, $user);

        $owner = new User();
        $owner->setEmail('owner@mail.ru')
              ->setPassword($this->userPasswordEncoder->encodePassword($user, '12345'))
              ->setEnabled('true');
        $manager->persist($owner);
        $manager->flush();

        $this->addReference(self::OWNER, $owner);
    }
}
