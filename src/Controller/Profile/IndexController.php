<?php

namespace App\Controller\Profile;

use App\Repository\CashboxRepository;
use App\Repository\CurrencyRepository;
use App\Repository\ExchangeOfficeRepository;
use App\Repository\TransactionsRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{

    /**
     * @Route("/profile/", name="profile_index")
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @param CashboxRepository $cashboxRepository
     * @param TransactionsRepository $transactionsRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(ExchangeOfficeRepository $exchangeOfficeRepository,
                                CashboxRepository $cashboxRepository,
                                TransactionsRepository $transactionsRepository)
    {
        $allExchange = $exchangeOfficeRepository->findByAllOwnersExchange($this->getUser());
        $all_total_point = $transactionsRepository->allTotalProfit($this->getUser()->getId());
        $all_number_of_operations = $transactionsRepository->allNumberOfOperations($this->getUser()->getId());
        $all_operations = $all_number_of_operations[0]['count_basic'] ?? 0 ;
        $all_point = $all_total_point[0]['summa'] ?? 0 ;

        $array = [];
        $arrayAllCurrency = [];
        foreach ($allExchange as $key => $exchange) {
            $exchangeName = $exchange['name'];
            $exchangeId = $exchange['id'];
            $exchangeOffice = $exchangeOfficeRepository->findByOne($exchangeId, $this->getUser());
            $cashboxs = $cashboxRepository->findByAll($exchangeOffice);

            foreach ($cashboxs as $key => $value) {
                if (isset($arrayAllCurrency[$value['iso']])) {
                    $arrayAllCurrency[$value['iso']] += $value['summa'];
                } else {
                    $arrayAllCurrency[$value['iso']] = $value['summa'];
                }
            }
            $arrayTotalPoint = $transactionsRepository->totalProfit($exchangeId);
            $totalPoint = $arrayTotalPoint[0]['summa'] ?? 0;

            $count_basic = $transactionsRepository->numberOfOperations($exchangeId);
            $numberOfOperations = $count_basic[0]['count_basic'] ?? 0;

            $array[] = [
                'exchange_name' => $exchangeName, 'exchange_id' => $exchangeId, 'cashboxs' => $cashboxs,
                'total_point' => $totalPoint, 'numberOfOperations' => $numberOfOperations
            ];
        }






        return $this->render('profile/index/index.html.twig',[
            'all_exchange' => $array,
            'all_currency' => $arrayAllCurrency,
            'all_total_point' => $all_point,
            'all_number_of_operations' => $all_operations
        ]);
    }

    /**
     * @Route("/profile_ajax/", name="profile_index_ajax")
     * @Method("POST")
     * @param ExchangeOfficeRepository $exchangeOfficeRepository

     * @param TransactionsRepository $transactionsRepository
     * @return JsonResponse
     */
    public function indexAjaxAction(ExchangeOfficeRepository $exchangeOfficeRepository,
                                    TransactionsRepository $transactionsRepository)
    {
        function sortedWithNext($days, $tomorrow) {
            $index_dictionary = array_combine(array_keys($days), range(0,6));
            $index_of_tomorrow = $index_dictionary[$tomorrow];
            $compare_function = function($k1, $k2) use ($index_dictionary, $index_of_tomorrow) {
                $index_of_k1 = $index_dictionary[$k1];
                $index_of_k2 = $index_dictionary[$k2];
                if($index_of_k1 < $index_of_tomorrow)
                    $index_of_k1 += 7;
                if($index_of_k2 < $index_of_tomorrow)
                    $index_of_k2 += 7;
                return $index_of_k1 - $index_of_k2;
            };
            uksort($days, $compare_function);
            return $days;
        }

        $allExchange = $exchangeOfficeRepository->findByAllOwnersExchange($this->getUser());
        $array = [];
        foreach ($allExchange as $key => $exchange) {
            $totalPoinOneDayData = $transactionsRepository->totalProfitOneDay($exchange['id']);
            $weekday = [
                'Sunday'=> 0,'Saturday'=> 0,'Friday'=> 0,
                'Thursday'=> 0,'Wednesday'=> 0,'Tuesday'=> 0,'Monday' => 0
            ];
            $first_day = date('l');
            foreach ($totalPoinOneDayData as $key => $val){
                foreach ($weekday as $key => $value){
                    if($val['days'] == $key ){
                        $weekday [$val['days']] = $val['margin'];

                    }
                }
            }
            $sortedDays = sortedWithNext($weekday, $first_day);
            $array[] = ['weekday' => $sortedDays, 'id' => $exchange['id']];
        }
     return new JsonResponse($array);
    }

    /**
     * @Route("/profile_ajax_all_exchange/", name="profile_index_ajax_all_exchange")
     * @Method("POST")
     * @param TransactionsRepository $transactionsRepository
     * @return JsonResponse
     */
    public function allDataExchangeAjaxAction( TransactionsRepository $transactionsRepository)
    {
        function sortedWithNext($days, $tomorrow) {
            $index_dictionary = array_combine(array_keys($days), range(0,6));
            $index_of_tomorrow = $index_dictionary[$tomorrow];
            $compare_function = function($k1, $k2) use ($index_dictionary, $index_of_tomorrow) {
                $index_of_k1 = $index_dictionary[$k1];
                $index_of_k2 = $index_dictionary[$k2];
                if($index_of_k1 < $index_of_tomorrow)
                    $index_of_k1 += 7;
                if($index_of_k2 < $index_of_tomorrow)
                    $index_of_k2 += 7;
                return $index_of_k1 - $index_of_k2;
            };
            uksort($days, $compare_function);
            return $days;
        }

        $allExchangeDatas = $transactionsRepository->allTotalProfitOneDay($this->getUser()->getID());
        $weekday = [
            'Sunday'=> 0,'Saturday'=> 0,'Friday'=> 0,
            'Thursday'=> 0,'Wednesday'=> 0,'Tuesday'=> 0,'Monday' => 0
        ];
        $first_d = date('l');
        foreach ($allExchangeDatas as $key => $val) {
            foreach ($weekday as $key => $value) {
                if ($val['weekday'] == $key) {
                    $weekday [$val['weekday']] = $val['margin'];
                }
            }
        }
        $sortedDays = sortedWithNext($weekday, $first_d);
        return new JsonResponse($sortedDays);
    }


}
