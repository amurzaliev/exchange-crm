<?php

use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\HttpKernel\KernelInterface;

class SecurityContext extends RawMinkContext
{
    /** @var  KernelInterface */
    protected $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    protected function getContainer()
    {
        return $this->kernel->getContainer();
    }

    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    protected function getRepository($entityName)
    {
        return $this->getEntityManager()->getRepository($entityName);
    }

    private function getExchangeOfficeIdByName($name)
    {
        /** @var \App\Repository\ExchangeOfficeRepository $exchangeOfficeRepository */
        $exchangeOfficeRepository = $this->getRepository(\App\Entity\ExchangeOffice::class);
        $exchangeOffice = $exchangeOfficeRepository->findOneBy(['name' => $name]);
        return $exchangeOffice->getId();
    }

    private function getCurrencyIdByName($name)
    {
        /** @var \App\Repository\CurrencyRepository $currencyRepository */
        $currencyRepository = $this->getRepository(\App\Entity\Currency::class);
        $currency = $currencyRepository->findOneBy(['name' => $name]);
        return $currency->getId();
    }

    /**
     * @When /^я перехожу на просмотр обменного пункта "([^"]*)"$/
     * @param $name
     */
    public function iViewExchangeOffice($name)
    {
        $path = $this->getContainer()
            ->get('router')
            ->generate('profile_exchange_office_detail', ['id' => $this->getExchangeOfficeIdByName($name)]);
        $this->visitPath($path);
    }

    /**
     * @When /^я перехожу на редактирование обменного пункта "([^"]*)"$/
     * @param $name
     */
    public function iEditExchangeOffice($name)
    {
        $path = $this->getContainer()
            ->get('router')
            ->generate('profile_exchange_office_edit', ['id' => $this->getExchangeOfficeIdByName($name)]);
        $this->visitPath($path);
    }

    /**
     * @When /^я перехожу на просмотр типа валюты "([^"]*)"$/
     * @param $name
     */
    public function iViewCurrency($name)
    {
        $path = $this->getContainer()
            ->get('router')
            ->generate('profile_currency_detail', ['id' => $this->getCurrencyIdByName($name)]);
        $this->visitPath($path);
    }

    /**
     * @When /^я перехожу на редактирование типа валюты "([^"]*)"$/
     * @param $name
     */
    public function iEditCurrency($name)
    {
        $path = $this->getContainer()
            ->get('router')
            ->generate('profile_currency_edit', ['id' => $this->getCurrencyIdByName($name)]);
        $this->visitPath($path);
    }

}