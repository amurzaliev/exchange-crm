<?php

namespace App\Controller\Profile;

use App\Entity\Transactions;
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
     * @param ObjectManager $manager
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @param CashboxRepository $cashboxRepository
     * @return JsonResponse
     */
    public function createAction(
        Request $request,
        ObjectManager $manager,
        ExchangeOfficeRepository $exchangeOfficeRepository,
        CashboxRepository $cashboxRepository
    )
    {
        $status = true;
        $message = "Данные успешно сохраннены!";
        $allAmount = 0;

        $user = $this->getUser();
        $exchangeOfficeId = intval($request->get("exchange_office_id"));
        $cashboxAction = $request->get("cashboxAction");
        $cashboxId = intval($request->get("cashboxId"));
        $currency = $request->get("currency");
        $amount = $request->get("amount");
        $nationalCurrency = $request->get("national_currency");

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

        try {

            $exchangeOffice = $exchangeOfficeRepository->findByOne($exchangeOfficeId, $this->getOwner());

            if (!$exchangeOffice) {
                throw new \Exception("Обменный пункт не найден.", 403);
            }

            $cashbox = $cashboxRepository->findByOne($cashboxId, $exchangeOffice);

            if (!$cashbox) {
                throw new \Exception("Касса не найденна.", 403);
            }

            if ($basicTypeOperations <= 0) {
                throw new \Exception("Неизвестный тип операции.", 403);
            }

            $transactions = new Transactions();

            if ($basicTypeOperations == 1) {
                $transactions->setсashboxTo($cashbox);
            } else if($basicTypeOperations == 2) {
                $transactions->setCashboxFrom($cashbox);
            }

            $transactions
                ->setBasicType($basicTypeOperations)
                ->setAmount($amount)
                ->setCurrentCourse($currency)
                ->setExchangeOffice($exchangeOffice)
                ->setUser($user)
                ->setNationalCurrency($nationalCurrency)
            ;

            $manager->persist($transactions);
            $manager->flush();

            $cashboxAmount = $cashboxRepository->getAllAmount($cashboxId, $exchangeOffice);

            if ($cashboxAmount) {
                $allAmount = $cashboxAmount[0]['summa'];
            }

        } catch (\Exception $e) {
            $status = false;
            if ($e->getCode() != 403 ) {
                $message = "Не изветсная ошибка. Обратитесь к администрации сайта.";
            } else {
                $message = $e->getMessage();
            }
        }

        return new JsonResponse([
           'status' => $status,
           'message' => $message,
           'allAmount' => $allAmount,

        ]);
    }
}
