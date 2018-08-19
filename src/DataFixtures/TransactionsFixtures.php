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
        /** @var User $user */
        $user = $this->getReference(UserFixtures::USER);
        /** @var Cashbox $cashbox */
        $cashbox = $this->getReference(CashboxFixtures::CASHBOX_TWO);
        $transactions = new Transactions();

        $transactions
            ->setCreatedAt(new \DateTime())
            ->setUpdateAt(new \DateTime())
            ->setUser($user)
            ->setсashboxTo($cashbox)
            ->setAmount(6000000)
            ->setNote("Стартовый каписал для Дефолтной кассы")
            ->setBasicType(1)
        ;

        $manager->persist($transactions);
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
            CashboxFixtures::class
        );
    }


}