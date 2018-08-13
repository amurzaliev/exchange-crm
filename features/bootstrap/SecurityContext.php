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

    private function getPermissionGroupIdByTitle($title)
    {
        /** @var \App\Repository\ExchangeOfficeRepository $exchangeOfficeRepository */
        $PermissionGroupRepository = $this->getRepository(\App\Entity\PermissionGroup::class);
        $permissionGroup = $PermissionGroupRepository->findOneBy(['title' => $title]);
        return $permissionGroup->getId();
    }

    private function getCurrencyIdByName($name)
    {
        /** @var \App\Repository\CurrencyRepository $currencyRepository */
        $currencyRepository = $this->getRepository(\App\Entity\Currency::class);
        $currency = $currencyRepository->findOneBy(['name' => $name]);
        return $currency->getId();

    }

    private function getCashboxIdByCurrency($currency)
    {
        /** @var \App\Repository\CurrencyRepository $currencyRepository */
        $cashboxRepository = $this->getRepository(\App\Entity\Cashbox::class);
        $cashbox = $cashboxRepository->findOneBy(['name' => $currency]);
        return $cashbox->getId();
    }

    private function getStaffIdByLogin($login)
    {
        /** @var \App\Repository\CurrencyRepository $currencyRepository */
        $staffRepository = $this->getRepository(\App\Entity\Staff::class);
        $staff = $staffRepository->findOneBy(['login' => $login]);
        return $staff->getId();
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

    /**
     * @When /^я перехожу на просмотр валютной кассы "([^"]*)"$/
     * @param $currency
     */
    public function iViewCashbox($currency)
    {
        $path = $this->getContainer()
            ->get('router')
            ->generate('profile_cashbox_detail', ['id' => $this->getCashboxIdByCurrency($currency)]);
        $this->visitPath($path);
    }

    /**
     * @When /^я перехожу на редактирование валютной кассы "([^"]*)"$/
     * @param $currency
     */
    public function iEditCashbox($currency)
    {
        $path = $this->getContainer()
            ->get('router')
            ->generate('profile_cashbox_edit', ['id' => $this->getCashboxIdByCurrency($currency)]);
        $this->visitPath($path);
    }


    /**
     * @When /^я перехожу на просмотр группы привилегий "([^"]*)"$/
     * @param $title
     */
    public function iViewPermissionGroup($title)
    {
        $path = $this->getContainer()
            ->get('router')
            ->generate('profile_permission_group_detail', ['id' => $this->getPermissionGroupIdByTitle($title)]);
        $this->visitPath($path);
    }

    /**
     * @When /^я перехожу на редактирование группы привилегий "([^"]*)"$/
     * @param $title
     */
    public function iEditPermissionGroup($title)
    {
        $path = $this->getContainer()
            ->get('router')
            ->generate('profile_permission_group_edit', ['id' => $this->getPermissionGroupIdByTitle($title)]);
        $this->visitPath($path);
    }


    /**
     * @When /^я перехожу на просмотр страницы сотрудника с логином "([^"]*)"$/
     * @param $login
     */
    public function iViewStaff($login)
    {
        $path = $this->getContainer()
            ->get('router')
            ->generate('profile_staff_detail', ['id' => $this->getStaffIdByLogin($login)]);
        $this->visitPath($path);
    }

    /**
     * @When /^я перехожу на редактирование страницы сотрудника с логином "([^"]*)"$/
     * @param $login
     */
    public function iEditStaff($login)
    {
        $path = $this->getContainer()
            ->get('router')
            ->generate('profile_staff_detail', ['id' => $this->getStaffIdByLogin($login)]);
        $this->visitPath($path);
    }
}