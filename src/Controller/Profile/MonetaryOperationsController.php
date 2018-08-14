<?php

namespace App\Controller\Profile;

use App\Entity\Transactions;
use App\Model\Transactions\TransactionsHandler;
use App\Repository\CashboxRepository;
use App\Repository\ExchangeOfficeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(
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

        return $this->render('profile/monetary_operations/index.html.twig', [
            'cashboxs' => $cashboxs,
            'exchangeOffice' => $exchangeOffice
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
        $resultAmount = 0;

        try {
            $resultAmount = $transactionsHandler->create($request, $this->getUser());
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
           'allAmount' => $resultAmount,
        ]);
    }
}
