<?php

namespace App\DataFixtures;

use App\Entity\Cashbox;
use App\Entity\Currency;
use App\Entity\ExchangeOffice;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CashboxFixtures extends Fixture implements DependentFixtureInterface
{
    const CASHBOX_ONE = 'cashbox_one';

    public function load(ObjectManager $manager)
    {
        /**
         * @var User $owner
         */
        $owner = $this->getReference(UserFixtures::OWNER);

        /**
         * @var ExchangeOffice $exchangeOffice
         */
        $exchangeOffice = $this->getReference(ExchangeOfficeFixtures::EXCHANGEOFFICE);

        /**
         * @var Currency $currency
         */
        $currency = $this->getReference('currency1');

        $cashbox = new Cashbox();
        $cashbox->setUser($owner)
            ->setExchangeOffice($exchangeOffice)
            ->setCurrency($currency)
            ->setCreatedAt(new \DateTime());

        $manager->persist($cashbox);
        $manager->flush();

        $this->setReference(self::CASHBOX_ONE, $cashbox);
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
            ExchangeOfficeFixtures::class,
            CurrencyFixtures::class
        );
    }
}
