<?php

namespace App\DataFixtures;

use App\Entity\Cashbox;
use App\Entity\Transactions;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TransactionsFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $cashboxes = CashboxFixtures::getCashboxes();

        foreach ($cashboxes as $cashboxRefId) {

            /** @var Cashbox $cashbox */
            $cashbox = $this->getReference($cashboxRefId);

            $transactions = new Transactions();
            $transactions
                ->setCreatedAt(new \DateTime('-20 days'))
                ->setUpdateAt(new \DateTime('-20 days'))
                ->setUser($cashbox->getUser())
                ->setCashboxTo($cashbox)
                ->setAmount(10000)
                ->setNote("Стартовый капитал для кассы {$cashbox->getExchangeOffice()->getName()}")
                ->setBasicType(1)
                ->setExchangeOffice($cashbox->getExchangeOffice());
            $manager->persist($transactions);
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
        return array(
            CashboxFixtures::class
        );
    }


}