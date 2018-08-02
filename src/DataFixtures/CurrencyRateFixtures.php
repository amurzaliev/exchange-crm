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
use App\Entity\User;
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
        /** @var User $user */
        $user = $this->getReference(UserFixtures::OWNER);
        /** @var Cashbox $cashbox */
        $cashbox = $this->getReference(CashboxFixtures::CASHBOX_ONE);

        $currencyRate = new CurrencyRate();
        $currencyRate
            ->setPurchase(68.05)
            ->setSale(68.15)
            ->setUser($user)
            ->setCashboxCurrency($cashbox);

        $manager->persist($currencyRate);
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