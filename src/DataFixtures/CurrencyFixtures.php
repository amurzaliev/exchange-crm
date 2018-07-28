<?php

namespace App\DataFixtures;

use App\Entity\Currency;
use App\Entity\ExchangeOffice;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CurrencyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $this->clearImages();
        $data = $this->generateData();

        foreach ($data as $item) {

            $currency = new Currency();
            $currency
                ->setName($item['name'])
                ->setImageFile(new UploadedFile($item['image'], $item['id'], null,null,null,true))
                ->setUser($this->getReference(UserFixtures::ADMIN));

            $this->setReference($item['id'], $currency);

            $manager->persist($currency);
            $manager->flush();
        }
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

    private function generateData(): array
    {
        $fixturesPath = __DIR__ . '/../../public/images/fixtures/';

        // Copying fixtures images
        copy($fixturesPath . 'icon1.jpg', $fixturesPath . 'currency1.jpg');
        copy($fixturesPath . 'icon2.jpg', $fixturesPath . 'currency2.jpg');

        return [
            [
                'id' => 'currency1',
                'image' => $fixturesPath . 'currency1.jpg',
                'name' => 'Доллар США'
            ],
            [
                'id' => 'currency2',
                'image' => $fixturesPath . 'currency2.jpg',
                'name' => 'Тенге'
            ]
        ];
    }

    private function clearImages()
    {
        $files = glob(__DIR__ . '/../../public/images/currency/*');
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }
    }
}
