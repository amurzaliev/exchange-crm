<?php

namespace App\Controller\Profile;

use App\Model\Transactions\TransactionsHandler;
use App\Repository\CashboxRepository;
use App\Repository\ExchangeOfficeRepository;
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
            if ($e->getCode() != 403 ) {
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
            if ($e->getCode() != 403 ) {
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

}
