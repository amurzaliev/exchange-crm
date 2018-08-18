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
        /** @var \App\Repository\PermissionGroupRepository $permissionGroupRepository */
        $permissionGroupRepository = $this->getRepository(\App\Entity\PermissionGroup::class);
        $permissionGroup = $permissionGroupRepository->findOneBy(['title' => $title]);
        return $permissionGroup->getId();
    }

    private function getCurrencyIdByName($name)
    {
        /** @var \App\Repository\CurrencyRepository $currencyRepository */
        $currencyRepository = $this->getRepository(\App\Entity\Currency::class);
        $currency = $currencyRepository->findOneBy(['name' => $name]);
        return $currency->getId();

    }

    private function getStaffIdByLogin($login)
    {
        /** @var \App\Repository\UserRepository $userRepository */
        $userRepository = $this->getRepository(\App\Entity\User::class);
        $staff = $userRepository->findOneBy(['username' => $login]);
        return $staff->getId();
    }

    private function getCashboxByName($name)
    {
        /** @var \App\Repository\currencyRepository $currencyRepository */
        $currencyRepository = $this->getRepository(\App\Entity\Currency::class);
        /** @var \App\Repository\CashboxRepository $cashboxRepository */
        $cashboxRepository = $this->getRepository(\App\Entity\Cashbox::class);
        $currency = $currencyRepository->findOneBy(['name' => $name]);
        $cashbox = $cashboxRepository->findOneBy(['currency'=> $currency->getId()]);
        return $cashbox->getId();
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
     * @param $name
     */
    public function iViewCashbox($name)
    {
        $path = $this->getContainer()
            ->get('router')
            ->generate('profile_cashbox_detail', ['id' => $this->getCashboxByName($name)]);
        $this->visitPath($path);
    }

    /**
     * @When /^я перехожу на редактирование валютной кассы "([^"]*)"$/
     * @param $name
     */
    public function iEditCashbox($name)
    {
        $path = $this->getContainer()
            ->get('router')
            ->generate('profile_cashbox_edit', ['id' => $this->getCashboxByName($name)]);
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