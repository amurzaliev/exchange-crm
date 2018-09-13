<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    public const ADMIN = 'admin';
    public const OWNER_ONE = 'owner-one';
    public const OWNER_TWO = 'owner-two';

    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();

        $admin
            ->setEmail('admin@mail.ru')
            ->setPlainPassword('12345')
            ->setRoles(['ROLE_ADMIN'])
            ->setFullName('Админ А.А')
            ->setEnabled('true');
        $manager->persist($admin);
        $manager->flush();
        $this->addReference(self::ADMIN, $admin);

        $owner1 = new User();
        $owner1
            ->setEmail('owner@mail.ru')
            ->setPlainPassword('12345')
            ->setRoles(['ROLE_OWNER'])
            ->setEnabled('true')
            ->setFullName('Владелец 1');
        $manager->persist($owner1);
        $this->addReference(self::OWNER_ONE, $owner1);

        $owner2 = new User();
        $owner2
            ->setEmail('owner2@mail.ru')
            ->setPlainPassword('12345')
            ->setRoles(['ROLE_OWNER'])
            ->setFullName('Владелец 2')
            ->setEnabled('true');
        $manager->persist($owner2);
        $this->addReference(self::OWNER_TWO, $owner2);

        $manager->flush();
    }
}
