<?php

namespace App\DataFixtures;

use App\Entity\Currency;
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
                ->setIso($item['iso'])
                ->setSymbolDesignation($item['symbolDesignation'])
                ->setDecimals($item['decimals'])
                ->setDecimalSeparator($item['decimalSeparator'])
                ->setThousandSeparator($item['thousandSeparator'])
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
        $fixturesPath = __DIR__ . '/../../public/fixtures/images/';

        // Copying fixtures images
        copy($fixturesPath . 'icon1.png', $fixturesPath . 'currency1.png');
        copy($fixturesPath . 'icon2.png', $fixturesPath . 'currency2.png');
        copy($fixturesPath . 'icon3.png', $fixturesPath . 'currency3.png');
        copy($fixturesPath . 'icon4.png', $fixturesPath . 'currency4.png');
        copy($fixturesPath . 'icon5.png', $fixturesPath . 'currency5.png');

        return [
            [
                'id' => 'currency1',
                'image' => $fixturesPath . 'currency1.png',
                'name' => 'Доллар США',
                'iso' => 'USD',
                'symbolDesignation' => '$',
                'decimals' => 2,
                'decimalSeparator' => '.',
                'thousandSeparator' => ','
            ],
            [
                'id' => 'currency2',
                'image' => $fixturesPath . 'currency2.png',
                'name' => 'Тенге',
                'iso' => 'KZT',
                'symbolDesignation' => 'KZT',
                'decimals' => 4,
                'decimalSeparator' => '.',
                'thousandSeparator' => ','
            ],
            [
                'id' => 'currency3',
                'image' => $fixturesPath . 'currency3.png',
                'name' => 'Евро',
                'iso' => 'EUR',
                'symbolDesignation' => 'EUR',
                'decimals' => 2,
                'decimalSeparator' => '.',
                'thousandSeparator' => '.'
            ],
            [
                'id' => 'currency4',
                'image' => $fixturesPath . 'currency4.png',
                'name' => 'Рубль',
                'iso' => 'RUB',
                'symbolDesignation' => 'RUB',
                'decimals' => 3,
                'decimalSeparator' => '.',
                'thousandSeparator' => '.'
            ],
            [
                'id' => 'currency5',
                'image' => $fixturesPath . 'currency5.png',
                'name' => 'Юань',
                'iso' => 'CNY',
                'symbolDesignation' => 'CNY',
                'decimals' => 2,
                'decimalSeparator' => '.',
                'thousandSeparator' => '.'
            ]
        ];
    }

    private function clearImages()
    {
        $files = glob(__DIR__ . '/../../public/uploads/images/currency/*');
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }
    }
}
