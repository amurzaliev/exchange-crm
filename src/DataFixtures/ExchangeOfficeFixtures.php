<?php

namespace App\DataFixtures;

use App\Entity\ExchangeOffice;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ExchangeOfficeFixtures extends Fixture implements DependentFixtureInterface
{

    public const EXCHANGEOFFICE = 'Обменный пункт №1';

    public function load(ObjectManager $manager)
    {
        /** @var User $owner */
        $owner = $this->getReference(UserFixtures::OWNER);

        $exchangeOffice = new ExchangeOffice();
        $exchangeOffice->setName(self::EXCHANGEOFFICE)
            ->setAddress('Абдрахманова, 125')
            ->setContact('0312545454, 0555545454')
            ->setActive('true')
            ->setUser($owner);
        $manager->persist($exchangeOffice);

        $manager->flush();

        $this->addReference(self::EXCHANGEOFFICE, $exchangeOffice);
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
