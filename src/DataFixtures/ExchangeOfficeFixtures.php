<?php

namespace App\DataFixtures;

use App\Entity\ExchangeOffice;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ExchangeOfficeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var User $owner */
        $owner = $this->getReference(UserFixtures::OWNER);

        $exchangeOffice = new ExchangeOffice();
        $exchangeOffice->setName('Обменный пункт №1')
            ->setAddress('Абдрахманова, 125')
            ->setContact('0312545454, 0555545454')
            ->setActive('true')
            ->setUser($owner);
        $manager->persist($exchangeOffice);

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
