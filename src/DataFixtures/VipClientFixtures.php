<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\VIPClient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class VipClientFixtures extends Fixture implements DependentFixtureInterface
{
    const VIP_CLIENT_ONE = 'vipClient_one';

    public function load(ObjectManager $manager)
    {
        /**
         * @var User $owner
         */
        $owner = $this->getReference(UserFixtures::OWNER);

        $vipClient= new VIPClient();
        $vipClient->setUser($owner)
            ->setFullName('Гарри Поттер')
            ->setEmail('hpotter@mail.ru')
            ->setPhone('996555555555')
            ->setCreatedAt(new \DateTime());

        $manager->persist($vipClient);
        $manager->flush();

        $this->setReference(self::VIP_CLIENT_ONE, $vipClient);
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
