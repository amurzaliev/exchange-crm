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

    /**
     * @When /^я просматриваю "([^"]*)"$/
     * @param $name
     */
    public function iViewExchangeOffice($name)
    {
        /** @var \App\Repository\ExchangeOfficeRepository $exchangeOfficeRepository */
        $exchangeOfficeRepository = $this->getRepository(\App\Entity\ExchangeOffice::class);
        $exchangeOffice = $exchangeOfficeRepository->findOneBy(['name' => $name]);
        $path = $this->getContainer()
            ->get('router')
            ->generate('profile_exchange_office_detail', ['id' => $exchangeOffice->getId()]);
        $this->visitPath($path);
    }

}