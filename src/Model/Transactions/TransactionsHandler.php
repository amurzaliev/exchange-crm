<?php

namespace App\Model\Transactions;

use App\Entity\Transactions;
use App\Entity\User;
use App\Model\ModelHandler;
use App\Repository\CashboxRepository;
use App\Repository\ExchangeOfficeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class TransactionsHandler extends ModelHandler
{
    private $exchangeOfficeRepository;
    private $cashboxRepository;

    public function __construct(
        ContainerInterface $container,
        ObjectManager $manager,
        ExchangeOfficeRepository $exchangeOfficeRepository,
        CashboxRepository $cashboxRepository
    )
    {
        parent::__construct($container, $manager);

        $this->exchangeOfficeRepository = $exchangeOfficeRepository;
        $this->cashboxRepository = $cashboxRepository;
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
        $resultAmount = 0;

        switch ($cashboxAction) {
            case "purchase" :
                $basicTypeOperations = 1;
                break;
            case "sale":
                $basicTypeOperations = 2;
                break;
            default:
                $basicTypeOperations = 0;
        }

        $exchangeOffice = $this->exchangeOfficeRepository->findByOne($exchangeOfficeId, $this->getOwner($user));

        $cashboxAmount = $this->cashboxRepository->getAllAmount($cashboxId, $exchangeOffice);

        if ($cashboxAmount) {
            $resultAmount = $cashboxAmount[0]['summa'];
        }

        if (!$exchangeOffice) {
            throw new \Exception("Обменный пункт не найден.", 403);
        }

        $cashbox = $this->cashboxRepository->findByOne($cashboxId, $exchangeOffice);

        if (!$cashbox) {
            throw new \Exception("Касса не найдена.", 403);
        }

        if ($basicTypeOperations <= 0) {
            throw new \Exception("Неизвестный тип операции.", 403);
        }

        $transactions = new Transactions();

        if ($basicTypeOperations == 1) {
            $transactions->setсashboxTo($cashbox);
            $resultAmount = number_format($resultAmount + $amount, 4, ".", "");
        } else if ($basicTypeOperations == 2) {
            $transactions->setCashboxFrom($cashbox);
            $resultAmount = number_format($resultAmount - $amount, 4, ".", "");

            if ($resultAmount < 0) {
                throw new \Exception("Недостаточно средств в кассе ".$cashbox->getCurrency()->getName()." для совершения операции.", 403);
            }
        }

        $transactions
            ->setBasicType($basicTypeOperations)
            ->setAmount($amount)
            ->setCurrentCourse($currency)
            ->setExchangeOffice($exchangeOffice)
            ->setUser($user)
            ->setNationalCurrency($nationalCurrency)
        ;

        $this->manager->persist($transactions);
        $this->manager->flush();

        return $resultAmount;
    }
}