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
    private static $cashboxes = [];

    public function load(ObjectManager $manager)
    {
        /** @var ExchangeOffice[] $exchangeOffices */
        $exchangeOffices = [
            $this->getReference(ExchangeOfficeFixtures::EXCHANGE_OFFICE_ONE),
            $this->getReference(ExchangeOfficeFixtures::EXCHANGE_OFFICE_TWO),
            $this->getReference(ExchangeOfficeFixtures::EXCHANGE_OFFICE_THREE),
            $this->getReference(ExchangeOfficeFixtures::EXCHANGE_OFFICE_FOUR)
        ];

        /** @var Currency $currencyUSD */
        $currencyUSD = $this->getReference('usd');
        /** @var Currency $currencyKGS */
        $currencyKGS = $this->getReference('kgs');
        /** @var Currency $currencyKZT */
        $currencyKZT = $this->getReference('kzt');
        /** @var Currency $currencyRUB */
        $currencyRUB = $this->getReference('rub');

        foreach ($exchangeOffices as $exchangeOffice) {
            $cashboxKGS = $this->createCashboxByCurrency($currencyKGS, $exchangeOffice);
            $manager->persist($cashboxKGS);
            $cashboxUSD = $this->createCashboxByCurrency($currencyUSD, $exchangeOffice);
            $manager->persist($cashboxUSD);
            $cashboxKZT = $this->createCashboxByCurrency($currencyKZT, $exchangeOffice);
            $manager->persist($cashboxKZT);
            $cashboxRUB = $this->createCashboxByCurrency($currencyRUB, $exchangeOffice);
            $manager->persist($cashboxRUB);
        }

        $manager->flush();
    }

    private function createCashboxByCurrency(Currency $currency, ExchangeOffice $exchangeOffice)
    {
        $cashbox = new Cashbox();
        $cashbox
            ->setUser($exchangeOffice->getUser())
            ->setExchangeOffice($exchangeOffice)
            ->setCurrency($currency);

        $refId = "cashbox{$exchangeOffice->getId()}{$currency->getId()}";
        $this->addReference($refId, $cashbox);
        self::$cashboxes[] = $refId;

        return $cashbox;
    }

    public static function getCashboxes(): array
    {
        return self::$cashboxes;
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
            ExchangeOfficeFixtures::class,
            CurrencyFixtures::class
        );
    }
}
