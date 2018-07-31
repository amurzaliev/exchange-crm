<?php

namespace App\DataFixtures;

use App\Entity\Staff;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class StaffFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        /** @var User $owner */
        $owner = $this->getReference(UserFixtures::OWNER);

        //Сотрудник 1
        $user1 = new User();
        $user1
            ->setUsername("staff_1")
            ->setPlainPassword("12345")
            ->setRoles(['ROLE_USER'])
            ->setFullName('Сосрудник А.А')
            ->setEnabled('true')
        ;

        $manager->persist($user1);

        $staff1 = new Staff();
        $staff1
            ->setOwner($owner)
            ->setUser($user1)
            ->setPosition("Администратор")
            ->setCreateAt(new \DateTime())
            ->setUpdateAt(new \DateTime())
        ;
        $manager->persist($staff1);

        //Сотрудник 2

        $user2 = new User();
        $user2
            ->setUsername("staff_2")
            ->setPlainPassword("12345")
            ->setRoles(['ROLE_USER'])
            ->setFullName('Сосрудник А.А')
            ->setEnabled('true')
        ;

        $manager->persist($user2);

        $staff2 = new Staff();
        $staff2
            ->setOwner($owner)
            ->setUser($user2)
            ->setPosition("Кассир")
            ->setCreateAt(new \DateTime())
            ->setUpdateAt(new \DateTime())
        ;
        $manager->persist($staff2);

        //Сотрудник 3

        $user3 = new User();
        $user3
            ->setUsername("staff_3")
            ->setPlainPassword("12345")
            ->setRoles(['ROLE_USER'])
            ->setFullName('Сосрудник А.А')
            ->setEnabled('true')
        ;

        $manager->persist($user3);

        $staff3 = new Staff();
        $staff3
            ->setOwner($owner)
            ->setUser($user3)
            ->setPosition("Партнер")
            ->setCreateAt(new \DateTime())
            ->setUpdateAt(new \DateTime())
        ;
        $manager->persist($staff3);

        $manager->flush();

    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return array(
            StaffFixtures::class,
        );
    }
}
