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
     * @return array
     * @throws \Exception
     */
    public function createPurchaseAndSaleOperation(Request $request, User $user)
    {

        $cashboxAction = $request->get("cashboxAction");
        $exchangeOfficeId = intval($request->get('exchange_office_id'));
        $vipClientId = intval($request->get('vip_client_id'));
        $cashboxId = intval($request->get('cashboxId'));
        $currency = $request->get('currency');
        $amount = $request->get('amount');
        $nationalCurrency = $request->get('national_currency');
        $notes = $request->get('notes');

        $exchangeOffice = $this->exchangeOfficeRepository->findByOne($exchangeOfficeId, $this->getOwner($user));
        $defaultCurrency = $this->cashboxRepository->findByOneDefaultCurrency($exchangeOffice);
        $cashboxDefaultAction = null;

        if ($cashboxAction == "purchase") {
            $cashboxAction = "up";
            $cashboxDefaultAction = "down";
        } else if($cashboxAction == "sale") {
            $cashboxAction = "down";
            $cashboxDefaultAction = "up";
        }

        $this->manager->getConnection()->beginTransaction();

        try {

            $resultDefaultCurrencyAmount = $this->saveTransaction([
                'cashboxAction' => $cashboxDefaultAction,
                'exchangeOfficeId' => $exchangeOfficeId,
                'cashboxId' => $defaultCurrency->getId(),
                'amount' => $nationalCurrency,
                'notes' => $notes,
                'user' => $user,
            ]);

            $resultAmount = $this->saveTransaction([
                'cashboxAction' => $cashboxAction,
                'exchangeOfficeId' => $exchangeOfficeId,
                'vipClientId' => $vipClientId,
                'cashboxId' => $cashboxId,
                'currency' => $currency,
                'amount' => $amount,
                'nationalCurrency' => $nationalCurrency,
                'notes' => $notes,
                'user' => $user,
            ]);

            $this->manager->commit();

            return [
                'resultAmount' => $resultAmount,
                'resultDefaultCurrencyAmount' => $resultDefaultCurrencyAmount
            ];

        } catch (\Exception $e) {
            $this->manager->getConnection()->rollBack();
            throw new \Exception($e->getMessage(), 403);
        }
    }

    /**
     * @param Request $request
     * @param User $user
     * @return int|string
     * @throws \Exception
     */
    public function balanceChange(Request $request, User $user)
    {
        $cashboxAction = $request->get("type_transaction");
        $exchangeOfficeId = $request->get("exchange_office_id");
        $cashboxId = $request->get("cashboxId");
        $amount = $request->get("amount");
        $notes = $request->get("notes");

        if ($cashboxAction == "replenishment") {
            $cashboxAction = "up";
        } else if($cashboxAction == "write_off") {
            $cashboxAction = "down";
        }

        return $this->saveTransaction([
            'cashboxAction' => $cashboxAction,
            'exchangeOfficeId' => $exchangeOfficeId,
            'cashboxId' => $cashboxId,
            'amount' => $amount,
            'notes' => $notes,
            'user' => $user,
        ]);
    }

    /**
     * @param array $data
     * @return int|string
     * @throws \Exception
     */
    public function saveTransaction(array $data)
    {

        $user = $data['user'];
        $amount = $data['amount'] ?? 0;
        $currency = $data['currency'] ?? null;
        $notes = $data['notes'] ?? null;
        $vipClientId = $data['vipClientId'] ?? 0;
        $exchangeOfficeId = $data['exchangeOfficeId'] ?? 0;
        $cashboxId = $data['cashboxId'] ?? 0;
        $nationalCurrency = $data['nationalCurrency'] ?? null;

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

        switch ($data['cashboxAction']) {
            case "up" :
                $transactions
                    ->setBasicType(1)
                    ->setCashboxTo($cashbox);
                $resultAmount = $this->countNumberAfterPoint($resultAmount + $amount);
                break;
            case "down":
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
            ->setVIPClient($vipClient ?? null)
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
}