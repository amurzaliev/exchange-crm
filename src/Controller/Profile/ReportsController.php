<?php

namespace App\Controller\Profile;

use App\Entity\Cashbox;
use App\Entity\ExchangeOffice;
use App\Entity\Transactions;
use App\Repository\CashboxRepository;
use App\Repository\ExchangeOfficeRepository;
use App\Repository\TransactionsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile/reports")
 *
 * Class ReportsController
 * @package App\Controller\Profile
 */
class ReportsController extends BaseProfileController
{
    /**
     * @Route("/", name="profile_reports_index")
     *
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @param TransactionsRepository $transactionsRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function indexAction(
        ExchangeOfficeRepository $exchangeOfficeRepository,
        TransactionsRepository $transactionsRepository
    )
    {
        $owner = $this->getOwner();

        $dates = new \DatePeriod(
            new \DateTime('-6 days'),
            new \DateInterval('P1D'),
            new \DateTime('+1 days')
        );

        $exchangeOffices = $exchangeOfficeRepository->findAllByOwnerRep($owner);
        $marginsData = $transactionsRepository->getAllMarginsByOwner($owner);

        $balances = [];
        $margins = [];

        /** @var ExchangeOffice $exchangeOffice */
        foreach ($exchangeOffices as $exchangeOffice) {
            /** @var Cashbox $cashbox */
            foreach ($exchangeOffice->getCashboxes() as $cashbox) {
                $balances[$cashbox->getId()] = [];
                $margins[$cashbox->getId()] = [];

                /** @var \DateTime $date */
                foreach ($dates as $date) {
                    $cashboxBalance = 0;

                    /** @var Transactions $transaction */
                    foreach ($cashbox->getTransactionsTo() as $transaction) {
                        if ($transaction->getCreatedAt() <= $date) {
                            $cashboxBalance += $transaction->getAmount();
                        }
                    }

                    /** @var Transactions $transaction */
                    foreach ($cashbox->getTransactionsFrom() as $transaction) {
                        if ($transaction->getCreatedAt() <= $date) {
                            $cashboxBalance -= $transaction->getAmount();
                        }
                    }

                    $balances[$cashbox->getId()][] = [
                        'date' => $date,
                        'amount' => $cashboxBalance
                    ];

                    $margin = 0;

                    foreach ($marginsData as $marginData) {
                        if ($cashbox->getId() === (int)$marginData['cashbox_id'] &&
                            $date->format('Y-m-d') === $marginData['t_date']
                        ) {
                            $margin = $marginData['t_margin'] === null ? 0 : $marginData['t_margin'];
                        }
                    }

                    $margins[$cashbox->getId()][] = [
                        'date' => $date,
                        'margin' => $margin
                    ];
                }
            }
        }

        $balanceBlock = $this->render('profile/reports/tables/balance.html.twig', [
            'dates' => $dates,
            'exchangeOffices' => $exchangeOffices,
            'balances' => $balances
        ])->getContent();

        $marginBlock = $this->render('profile/reports/tables/margin.html.twig', [
            'dates' => $dates,
            'exchangeOffices' => $exchangeOffices,
            'margins' => $margins
        ])->getContent();

        return $this->render('profile/reports/index.html.twig', [
            'balanceBlock' => $balanceBlock,
            'marginBlock' => $marginBlock
        ]);
    }

    /**
     * @Route("/update-balance-ajax", name="profile_reports_update_balance_ajax")
     *
     * @param Request $request
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @return JsonResponse
     */
    public function updateBalanceAjaxAction(
        Request $request,
        ExchangeOfficeRepository $exchangeOfficeRepository
    )
    {
        $message = 'success';

        try {

            $owner = $this->getOwner();
            $currentDate = new \DateTime();

            $dateFrom = \DateTime::createFromFormat('d/m/Y', $request->request->get('balanceDateFrom'));
            $dateTo = \DateTime::createFromFormat('d/m/Y', $request->request->get('balanceDateTo'));
            if ($dateTo > $currentDate) {
                $dateTo = $currentDate;
            }

            $dates = new \DatePeriod(
                $dateFrom,
                new \DateInterval('P1D'),
                $dateTo->modify('+1 day')
            );

            $exchangeOffices = $exchangeOfficeRepository->findAllByOwnerRep($owner);
            $balances = [];

            /** @var ExchangeOffice $exchangeOffice */
            foreach ($exchangeOffices as $exchangeOffice) {
                /** @var Cashbox $cashbox */
                foreach ($exchangeOffice->getCashboxes() as $cashbox) {
                    $balances[$cashbox->getId()] = [];

                    foreach ($dates as $date) {
                        $cashboxBalance = 0;

                        /** @var Transactions $transaction */
                        foreach ($cashbox->getTransactionsTo() as $transaction) {
                            if ($transaction->getCreatedAt() <= $date) {
                                $cashboxBalance += $transaction->getAmount();
                            }
                        }

                        /** @var Transactions $transaction */
                        foreach ($cashbox->getTransactionsFrom() as $transaction) {
                            if ($transaction->getCreatedAt() <= $date) {
                                $cashboxBalance -= $transaction->getAmount();
                            }
                        }

                        $balances[$cashbox->getId()][] = [
                            'date' => $date,
                            'amount' => $cashboxBalance
                        ];
                    }
                }
            }

            $balanceBlock = $this->render('profile/reports/tables/balance.html.twig', [
                'dates' => $dates,
                'exchangeOffices' => $exchangeOffices,
                'balances' => $balances
            ])->getContent();

        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return new JsonResponse(['balanceBlock' => $balanceBlock, 'message' => $message]);
    }

    /**
     * @Route("/update-margin-ajax", name="profile_reports_update_margin_ajax")
     *
     * @param Request $request
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @param TransactionsRepository $transactionsRepository
     * @return JsonResponse
     */
    public function updateMarginAjaxAction(
        Request $request,
        ExchangeOfficeRepository $exchangeOfficeRepository,
        TransactionsRepository $transactionsRepository
    )
    {
        $message = 'success';

        try {

            $owner = $this->getOwner();
            $currentDate = new \DateTime();

            $dateFrom = \DateTime::createFromFormat('d/m/Y', $request->request->get('marginDateFrom'));
            $dateTo = \DateTime::createFromFormat('d/m/Y', $request->request->get('marginDateTo'));
            if ($dateTo > $currentDate) {
                $dateTo = $currentDate;
            }

            $dates = new \DatePeriod(
                $dateFrom,
                new \DateInterval('P1D'),
                $dateTo->modify('+1 day')
            );

            $exchangeOffices = $exchangeOfficeRepository->findAllByOwnerRep($owner);
            $marginsData = $transactionsRepository->getAllMarginsByOwner($owner);

            $margins = [];

            /** @var ExchangeOffice $exchangeOffice */
            foreach ($exchangeOffices as $exchangeOffice) {
                /** @var Cashbox $cashbox */
                foreach ($exchangeOffice->getCashboxes() as $cashbox) {
                    $margins[$cashbox->getId()] = [];

                    /** @var \DateTime $date */
                    foreach ($dates as $date) {

                        $margin = 0;

                        foreach ($marginsData as $marginData) {
                            if ($cashbox->getId() === (int)$marginData['cashbox_id'] &&
                                $date->format('Y-m-d') === $marginData['t_date']
                            ) {
                                $margin = $marginData['t_margin'] === null ? 0 : $marginData['t_margin'];
                            }
                        }

                        $margins[$cashbox->getId()][] = [
                            'date' => $date,
                            'margin' => $margin
                        ];
                    }
                }
            }

            $marginBlock = $this->render('profile/reports/tables/margin.html.twig', [
                'dates' => $dates,
                'exchangeOffices' => $exchangeOffices,
                'margins' => $margins
            ])->getContent();

        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return new JsonResponse(['marginBlock' => $marginBlock, 'message' => $message]);
    }

}