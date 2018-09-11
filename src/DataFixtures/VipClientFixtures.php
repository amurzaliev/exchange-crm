<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\VIPClient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class VipClientFixtures extends Fixture implements DependentFixtureInterface
{
    const VIP_CLIENT_ONE = 'vip-client-one';
    const VIP_CLIENT_TWO = 'vip-client-one';

    public function load(ObjectManager $manager)
    {
        /** @var User $owner1 */
        $owner1 = $this->getReference(UserFixtures::OWNER_ONE);

        $vipClient1 = new VIPClient();
        $vipClient1->setUser($owner1)
            ->setFullName('Гарри Поттер')
            ->setEmail('hpotter@mail.ru')
            ->setPhone('996555555555');
        $manager->persist($vipClient1);
        $this->setReference(self::VIP_CLIENT_ONE, $vipClient1);

        /** @var User $owner2 */
        $owner2 = $this->getReference(UserFixtures::OWNER_TWO);

        $vipClient2 = new VIPClient();
        $vipClient2->setUser($owner2)
            ->setFullName('Рон Визли')
            ->setEmail('rwesley@mail.ru')
            ->setPhone('99677512193');
        $manager->persist($vipClient2);
        $this->setReference(self::VIP_CLIENT_TWO, $vipClient2);

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
