<?php

namespace App\DataFixtures;

use App\Entity\ExchangeOffice;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ExchangeOfficeFixtures extends Fixture implements DependentFixtureInterface
{

    public const EXCHANGE_OFFICE_ONE = 'exchange-office-one';
    public const EXCHANGE_OFFICE_TWO = 'exchange-office-two';
    public const EXCHANGE_OFFICE_THREE = 'exchange-office-three';
    public const EXCHANGE_OFFICE_FOUR = 'exchange-office-four';

    public function load(ObjectManager $manager)
    {
        /** @var User $owner1 */
        $owner1 = $this->getReference(UserFixtures::OWNER_ONE);
        /** @var User $owner2 */
        $owner2 = $this->getReference(UserFixtures::OWNER_TWO);

        $exchangeOffice4 = new ExchangeOffice();
        $exchangeOffice4->setName('Обменный пункт Абдрахманова')
            ->setAddress('Абдрахманова, 125')
            ->setContact('0312545454, 0555545454')
            ->setActive('true')
            ->setUser($owner1);
        $manager->persist($exchangeOffice4);
        $this->addReference(self::EXCHANGE_OFFICE_ONE, $exchangeOffice4);

        $exchangeOffice4 = new ExchangeOffice();
        $exchangeOffice4->setName('Обменный пункт Московская')
            ->setAddress('Московская, 125')
            ->setContact('0555665566')
            ->setActive('true')
            ->setUser($owner1);
        $manager->persist($exchangeOffice4);
        $this->addReference(self::EXCHANGE_OFFICE_TWO, $exchangeOffice4);

        $exchangeOffice4 = new ExchangeOffice();
        $exchangeOffice4->setName('Обменный пункт Киевская')
            ->setAddress('Киевская, 16')
            ->setContact('0555778877')
            ->setActive('true')
            ->setUser($owner2);
        $manager->persist($exchangeOffice4);
        $this->addReference(self::EXCHANGE_OFFICE_THREE, $exchangeOffice4);

        $exchangeOffice4 = new ExchangeOffice();
        $exchangeOffice4->setName('Обменный пункт Юнусалиева')
            ->setAddress('Юнусалиева, 16')
            ->setContact('0773658492')
            ->setActive('true')
            ->setUser($owner2);
        $manager->persist($exchangeOffice4);
        $this->addReference(self::EXCHANGE_OFFICE_FOUR, $exchangeOffice4);

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
