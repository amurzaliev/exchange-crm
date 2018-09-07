<?php
/**
 * Created by PhpStorm.
 * User: adilet
 * Date: 31.07.2018
 * Time: 19:47
 */

namespace App\DataFixtures;

use App\Entity\Cashbox;
use App\Entity\CurrencyRate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CurrencyRateFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $cashboxes = CashboxFixtures::getCashboxes();

        $rates = [
            'USD' => [68.05, 68.15],
            'KZT' => [0.181, 0.188],
            'RUB' => [1.01, 1.02]
        ];

        foreach ($cashboxes as $cashboxRefId) {

            /** @var Cashbox $cashbox */
            $cashbox = $this->getReference($cashboxRefId);

            if ($cashbox->getCurrency()->getIso() !== 'KGS') {
                $currencyRate = new CurrencyRate();
                $currencyRate
                    ->setPurchase($rates[$cashbox->getCurrency()->getIso()][0])
                    ->setSale($rates[$cashbox->getCurrency()->getIso()][1])
                    ->setUser($cashbox->getUser())
                    ->setCashboxCurrency($cashbox);
                $manager->persist($currencyRate);
            }
        }

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
        return [
            UserFixtures::class,
            CashboxFixtures::class
        ];
    }
}