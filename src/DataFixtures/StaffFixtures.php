<?php

namespace App\DataFixtures;

use App\Entity\ExchangeOffice;
use App\Entity\Staff;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class StaffFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var User $owner1 */
        $owner1 = $this->getReference(UserFixtures::OWNER_ONE);

        $user1 = $this->createUser('staff_1', '12345', 'Иванов А.А');
        $staff1 = $this->createStaff($owner1, $user1, 'Администратор');
        $manager->persist($user1);
        $manager->persist($staff1);

        $user2 = $this->createUser('staff_2', '12345', 'Петров А.А');
        $staff2 = $this->createStaff($owner1, $user2, 'Кассир');
        $manager->persist($user2);
        $manager->persist($staff2);

        $user3 = $this->createUser('staff_3', '12345', 'Сидоров А.А');
        $staff3 = $this->createStaff($owner1, $user3, 'Кассир');
        $manager->persist($user3);
        $manager->persist($staff3);

        /** @var User $owner2 */
        $owner2 = $this->getReference(UserFixtures::OWNER_TWO);

        $user4 = $this->createUser('staff_4', '12345', 'Глушков А.А');
        $staff4 = $this->createStaff($owner2, $user4, 'Администратор');
        $manager->persist($user4);
        $manager->persist($staff4);

        $user5 = $this->createUser('staff_5', '12345', 'Козлов А.А');
        $staff5 = $this->createStaff($owner2, $user5, 'Кассир');
        $manager->persist($user5);
        $manager->persist($staff5);

        $user6 = $this->createUser('staff_6', '12345', 'Игнатьевич А.А');
        $staff6 = $this->createStaff($owner2, $user6, 'Кассир');
        $manager->persist($user6);
        $manager->persist($staff6);

        $manager->flush();
    }

    private function createUser(string $username, string $plainPassword, string $fullname)
    {
        $user = new User();
        $user
            ->setUsername($username)
            ->setPlainPassword($plainPassword)
            ->setRoles(['ROLE_USER'])
            ->setFullName($fullname)
            ->setEnabled('true');

        return $user;
    }

    private function createStaff(User $owner, User $user, string $position)
    {
        $staff = new Staff();
        $staff
            ->setOwner($owner)
            ->setUser($user)
            ->setPosition($position);

        return $staff;
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
            UserFixtures::class,
            ExchangeOfficeFixtures::class
        );
    }
}
