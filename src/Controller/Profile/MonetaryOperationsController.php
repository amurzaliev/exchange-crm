<?php

namespace App\Controller\Profile;

use App\Model\Transactions\TransactionsHandler;
use App\Repository\CashboxRepository;
use App\Repository\CurrencyRepository;
use App\Repository\ExchangeOfficeRepository;
use App\Repository\StaffRepository;
use App\Repository\TransactionsRepository;
use App\Repository\VIPClientRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MonetaryOperationsController
 * @package App\Controller\Profile
 *
 * @Route("profile/monetary_operations")
 *
 */
class MonetaryOperationsController extends BaseProfileController
{
    /**
     * @Route("/exchange_office/{id}", name="profile_monetary_operations")
     * @Method("GET")
     * @param int $id
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @param CashboxRepository $cashboxRepository
     * @param VIPClientRepository $clientRepository
     * @return Response
     */
    public function indexAction(
        int $id,
        ExchangeOfficeRepository $exchangeOfficeRepository,
        CashboxRepository $cashboxRepository,
        VIPClientRepository $clientRepository
    )
    {
        $exchangeOffice = $exchangeOfficeRepository->findByOne($id, $this->getOwner());
        $vipClients = $clientRepository->findByOwner($this->getOwner());

        if (!$exchangeOffice) {
            return $this->show404();
        }

        $cashboxs = $cashboxRepository->findByAll($exchangeOffice);
        $defaultCurrency = $cashboxRepository->findByOneDefaultCurrency($exchangeOffice);
        $defaultCurrencyAmount = $cashboxRepository->getAllAmount($defaultCurrency->getId(), $exchangeOffice)[0];

        return $this->render('profile/monetary_operations/index.html.twig', [
            'cashboxs' => $cashboxs,
            'exchangeOffice' => $exchangeOffice,
            'vipClients' => $vipClients,
            'defaultCurrencyAmount' => $defaultCurrencyAmount,
            'defaultCurrency' => $defaultCurrency,
        ]);
    }

    /**
     * @Route("/create", name="profile_monetary_operations_create")
     * @Method({"POST"})
     * @param Request $request
     * @param TransactionsHandler $transactionsHandler
     * @return JsonResponse
     */
    public function createAction(
        Request $request,
        TransactionsHandler $transactionsHandler
    )
    {
        $status = true;
        $message = "Данные успешно сохранены!";
        $result = 0;

        try {
            $result = $transactionsHandler->createPurchaseAndSaleOperation($request, $this->getUser());
        } catch (\Exception $e) {
            $status = false;
            if ($e->getCode() != 403) {
                $message = "Неизвестная ошибка. Обратитесь к администрации сайта.";
            } else {
                $message = $e->getMessage();
            }
        }

        return new JsonResponse([
            'status' => $status,
            'message' => $message,
            'allAmount' => $result['resultAmount'],
            'resultDefaultCurrencyAmount' => $result['resultDefaultCurrencyAmount'],
        ]);
    }

    /**
     * @Route("/balance/exchange_office/{id}", name="profile_monetary_operations_balance")
     * @Method("GET")
     * @param int $id
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @param CashboxRepository $cashboxRepository
     * @return Response
     */
    public function balanceAction(
        int $id,
        ExchangeOfficeRepository $exchangeOfficeRepository,
        CashboxRepository $cashboxRepository
    )
    {
        $exchangeOffice = $exchangeOfficeRepository->findByOne($id, $this->getOwner());

        if (!$exchangeOffice) {
            return $this->show404();
        }

        $cashboxs = $cashboxRepository->findByAll($exchangeOffice);

        return $this->render('profile/monetary_operations/balance.html.twig', [
            'cashboxs' => $cashboxs,
            'exchangeOffice' => $exchangeOffice
        ]);
    }

    /**
     * @Route("/balance_change", name="profile_monetary_operations_balance_change")
     * @Method({"POST"})
     * @param Request $request
     * @param TransactionsHandler $transactionsHandler
     * @return JsonResponse
     */
    public function balanceChangeAction(
        Request $request,
        TransactionsHandler $transactionsHandler)
    {
        $status = true;
        $message = "Данные успешно сохранены!";
        $resultAmount = 0;

        try {
            $resultAmount = $transactionsHandler->balanceChange($request, $this->getUser());
        } catch (\Exception $e) {
            $status = false;
            if ($e->getCode() != 403) {
                $message = "Неизвестная ошибка. Обратитесь к администрации сайта.";
            } else {
                $message = $e->getMessage();
            }
        }

        return new JsonResponse([
            'status' => $status,
            'message' => $message,
            'resultAmount' => $resultAmount,
        ]);
    }

    /**
     * @Route("/history", name="profile_monetary_operations_history")
     * @Method("GET")
     *
     * @param Request $request
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @param StaffRepository $staffRepository
     * @param CurrencyRepository $currencyRepository
     * @param TransactionsRepository $transactionsRepository
     * @return Response
     */
    public function historyAction(
        Request $request,
        ExchangeOfficeRepository $exchangeOfficeRepository,
        StaffRepository $staffRepository,
        CurrencyRepository $currencyRepository,
        TransactionsRepository $transactionsRepository
    )
    {
        $user = $this->getOwner();
        $filter = $request->query->all();

        $exchangeOffices = $exchangeOfficeRepository->findByAllOwnersExchange($user);
        $currencies = $currencyRepository->findAllByOwner($user);
        $staffs = $staffRepository->findByAllOwnerStaff($user);
        $transactions = $transactionsRepository->getAllTransactionsByFilter($user, $filter);

        $blockList = $this->render('profile/monetary_operations/ajax/list_block.html.twig', [
            'transactions' => $transactions
        ])->getContent();

        return $this->render('profile/monetary_operations/history.html.twig', [
            'exchangeOffices' => $exchangeOffices,
            'currencies' => $currencies,
            'staffs' => $staffs,
            'blockList' => $blockList,
            'filter' => $filter
        ]);
    }

    /**
     * @Route("/history-ajax", name="profile_monetary_operations_history_ajax")
     *
     * @param Request $request
     * @param TransactionsRepository $transactionsRepository
     * @return Response
     */
    public function historyAjaxAction(
        Request $request,
        TransactionsRepository $transactionsRepository
    )
    {
        $status = true;
        $message = 'Данные обновлены';

        try {
            $user = $this->getOwner();
            $filter = $request->query->all();

            $transactions = $transactionsRepository->getAllTransactionsByFilter($user, $filter);

            $blockList = $this->render('profile/monetary_operations/ajax/list_block.html.twig', [
                'transactions' => $transactions
            ])->getContent();
        } catch (\Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }

        return new JsonResponse([
            'blockList' => $blockList,
            'status' => $status,
            'message' => $message
        ]);
    }

}
