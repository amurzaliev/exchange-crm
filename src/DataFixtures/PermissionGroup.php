<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PermissionGroup extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var User $user */
        $user = $this->getReference(UserFixtures::OWNER_ONE);

        $permissionGroup = new \App\Entity\PermissionGroup();
        $permissionGroup->setTitle('оператор')
            ->setAlias('operator')
            ->setCreatePersonal(false)
            ->setEditPersonal(false)
            ->setViewPersonal(true)
            ->setUser($user);

        $manager->persist($permissionGroup);

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
        UserFixtures::class,
    );
    }
}
