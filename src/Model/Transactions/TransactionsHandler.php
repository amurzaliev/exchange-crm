<?php

namespace App\Model\Transactions;

use App\Entity\Transactions;
use App\Entity\User;
use App\Model\ModelHandler;
use App\Repository\CashboxRepository;
use App\Repository\ExchangeOfficeRepository;
use App\Repository\VIPClientRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class TransactionsHandler extends ModelHandler
{
    private $exchangeOfficeRepository;
    private $cashboxRepository;
    private $VIPClientRepository;

    public function __construct(
        ContainerInterface $container,
        ObjectManager $manager,
        ExchangeOfficeRepository $exchangeOfficeRepository,
        CashboxRepository $cashboxRepository,
        VIPClientRepository $VIPClientRepository
    )
    {
        parent::__construct($container, $manager);

        $this->exchangeOfficeRepository = $exchangeOfficeRepository;
        $this->cashboxRepository = $cashboxRepository;
        $this->VIPClientRepository = $VIPClientRepository;
    }

    /**
     * @param Request $request
     * @param User $user
     * @return int|string
     * @throws \Exception
     */
    public function create(Request $request, User $user)
    {
        $exchangeOfficeId = intval($request->get("exchange_office_id"));
        $cashboxAction = $request->get("cashboxAction");
        $cashboxId = intval($request->get("cashboxId"));
        $currency = $request->get("currency");
        $amount = $request->get("amount");
        $nationalCurrency = $request->get("national_currency");
        $vipClientId = intval($request->get("vip_client_id"));
        $notes = $request->get("notes");

        $owner = $this->getOwner($user);
        $exchangeOffice = $this->exchangeOfficeRepository->findByOne($exchangeOfficeId, $owner);
        $cashbox = $this->cashboxRepository->findByOne($cashboxId, $exchangeOffice);
        $cashboxAmount = $this->cashboxRepository->getAllAmount($cashboxId, $exchangeOffice);
        $vipClient = $this->VIPClientRepository->findByOneOwner($vipClientId, $owner);

        $transactions = new Transactions();

        if ($cashboxAmount) {
            $resultAmount = $cashboxAmount[0]['summa'];
        } else {
            $resultAmount = 0;
        }

        if (!$exchangeOffice) {
            throw new \Exception("Обменный пункт не найден.", 403);
        }

        if (!$cashbox) {
            throw new \Exception("Касса не найдена.", 403);
        }

        switch ($cashboxAction) {
            case "purchase" :
                $transactions
                    ->setBasicType(1)
                    ->setсashboxTo($cashbox);
                $resultAmount = $this->countNumberAfterPoint($resultAmount + $amount);
                break;
            case "sale":
                $transactions
                    ->setBasicType(2)
                    ->setCashboxFrom($cashbox);
                $resultAmount = $this->countNumberAfterPoint($resultAmount - $amount);

                if ($resultAmount < 0) {
                    throw new \Exception("Недостаточно средств в кассе ".$cashbox->getCurrency()->getName()." для совершения операции.", 403);
                }

                break;
            default:
                throw new \Exception("Неизвестный тип операции.", 403);
        }

        $transactions
            ->setAmount($amount)
            ->setCurrentCourse($currency)
            ->setExchangeOffice($exchangeOffice)
            ->setUser($user)
            ->setNationalCurrency($nationalCurrency)
            ->setVIPClient($vipClient)
            ->setNote($notes)
        ;

        $this->manager->persist($transactions);
        $this->manager->flush();

        return $resultAmount;
    }

    public function countNumberAfterPoint($number, $decimals = 4, $decPoint = ".")
    {
        return number_format($number, $decimals, $decPoint, "");
    }

    /**
     * @param Request $request
     * @param User $user
     * @return int|string
     * @throws \Exception
     */
    public function balanceChange(Request $request, User $user)
    {
        $exchangeOfficeId = intval($request->get("exchange_office_id"));
        $typeTransaction = $request->get("type_transaction");
        $cashboxId = intval($request->get("cashboxId"));
        $amount = $request->get("amount");
        $notes = $request->get("notes");

        $owner = $this->getOwner($user);
        $exchangeOffice = $this->exchangeOfficeRepository->findByOne($exchangeOfficeId, $owner);
        $cashbox = $this->cashboxRepository->findByOne($cashboxId, $exchangeOffice);
        $cashboxAmount = $this->cashboxRepository->getAllAmount($cashboxId, $exchangeOffice);

        $transactions = new Transactions();

        if ($cashboxAmount) {
            $resultAmount = $cashboxAmount[0]['summa'];
        } else {
            $resultAmount = 0;
        }

        if (!$exchangeOffice) {
            throw new \Exception("Обменный пункт не найден.", 403);
        }

        if (!$cashbox) {
            throw new \Exception("Касса не найдена.", 403);
        }

        switch ($typeTransaction) {
            case "replenishment" :
                $transactions
                    ->setBasicType(1)
                    ->setсashboxTo($cashbox);
                $resultAmount = $this->countNumberAfterPoint($resultAmount + $amount);
                break;
            case "write_off":
                $transactions
                    ->setBasicType(2)
                    ->setCashboxFrom($cashbox);
                $resultAmount = $this->countNumberAfterPoint($resultAmount - $amount);

                if ($resultAmount < 0) {
                    throw new \Exception("Недостаточно средств в кассе ".$cashbox->getCurrency()->getName()." для совершения операции.", 403);
                }

                break;
            default:
                throw new \Exception("Неизвестный тип операции.", 403);
        }

        $transactions
            ->setAmount($amount)
            ->setExchangeOffice($exchangeOffice)
            ->setUser($user)
            ->setNote($notes)
        ;

        $this->manager->persist($transactions);
        $this->manager->flush();

        return $resultAmount;
    }
}