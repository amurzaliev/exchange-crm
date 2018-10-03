<?php

namespace App\Controller\Profile;

use App\Entity\Cashbox;
use App\Entity\CurrencyRate;
use App\Entity\ExchangeOffice;
use App\Entity\User;
use App\Form\CurrencyRateType;
use App\Repository\CashboxRepository;
use App\Repository\CurrencyRepository;
use App\Repository\ExchangeOfficeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CurrencyRateController
 * @package App\Controller\Profile
 *
 * @Route("profile/currency_rate")
 */
class CurrencyRateController extends BaseProfileController
{
    /**
     * @Route("/", name="profile_currency_rate_index")
     * @Method({"GET", "POST"})
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @param CashboxRepository $cashboxRepository
     * @return Response
     */
    public function indexAction(

        ExchangeOfficeRepository $exchangeOfficeRepository,
        CashboxRepository $cashboxRepository
    )
    {
        $exchangeOfficesListId = $exchangeOfficeRepository->getIdArrayExchangeOffice($this->getOwner());
        $currencys = $cashboxRepository->findByAllCashboxes($exchangeOfficesListId);

        $exchangeOffices = $exchangeOfficeRepository->getCurrencyRateExchangeOffice($this->getOwner(), $currencys);

        return $this->render('profile/currency_rate/index.html.twig', [
            'currencys' => $currencys,
            'exchangeOffices' => $exchangeOffices
        ]);

    }
    /**
     * @Route("/{id}/detail", name="profile_currency_rate_detail", requirements={"id"="\d+"})
     * @Method("GET")
     *
     * @param Cashbox $cashbox
     * @return Response
     */
    public function detailAction(Cashbox $cashbox)
    {
        $rates = $cashbox->getCurrencyRates();

        return $this->render('profile/currency_rate/detail.html.twig', [
            'cashbox' => $cashbox,
            'rates' => $rates
        ]);
    }

    /**
     * @Route("/{id}/create", name="profile_currency_rate_create", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Cashbox $cashbox
     * @return Response
     */
    public function createAction(Request $request, EntityManagerInterface $manager, Cashbox $cashbox)
    {
        $currencyRate = new CurrencyRate();
        $form = $this->createForm(CurrencyRateType::class, $currencyRate);
        $form->handleRequest($request);

        /** @var User $user */
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $currencyRate
                ->setUser($user)
                ->setCashboxCurrency($cashbox);

            $manager->persist($currencyRate);
            $manager->flush();

            return $this->redirectToRoute('profile_currency_rate_show', ['id' => $cashbox->getId()]);
        }

        return $this->render('profile/currency_rate/create.html.twig', [
            'cashbox' => $cashbox,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route( "/ajax_all_exchange_owner", name="profile_all_owner_exchanges")
     * @Method("POST")
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @return Response
     */
    public function allOwnerExchanges(ExchangeOfficeRepository $exchangeOfficeRepository)
    {
        /** @var User $user */
        $user = $this->getUser();
        $exchangeOffices = $exchangeOfficeRepository->findByAllOwnersExchange($user);

        return new JsonResponse($exchangeOffices);
    }

    /**
     * @Route( "/ajax_all_owner's_currencies", name="profile_all_owners_currencies")
     * @Method("POST")
     * @param CurrencyRepository $currencyRepository
     * @return Response
     */
    public function allOwnerCurrencies(CurrencyRepository $currencyRepository)
    {
        /** @var User $user */
        $user = $this->getUser();

        $currenciesAll = $currencyRepository->findByCurrency($user->getId());

        $currencies = [];
        foreach ($currenciesAll as $currency)
        {
            $default_currency = false;
            if($currency['default_currency']){
                $default_currency = true;
            }
            $array = [];
            $array['name'] = $currency['name'];
            $array['icon'] = $currency['icon'];
            $array['iso'] = $currency['iso'];
            $array['default_currency'] = $default_currency;
            $currencies[] = $array;
        }

        return new JsonResponse($currencies);
    }

    /**
     * @Route( "/ajax_find_by_exchange_office", name="profile_find_by_exchange_office")
     * @Method("POST")
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @param CashboxRepository $cashboxRepository
     * @return JsonResponse
     */
    public function findByExchangeOffice(ExchangeOfficeRepository $exchangeOfficeRepository, CashboxRepository $cashboxRepository)
    {
        $id = $_POST['id'];
        /** @var User $user */
        $user = $this->getUser();

        $exchangeOffice = $exchangeOfficeRepository->find($id);
        $cashboxs = $cashboxRepository->findByExchangeOffice($exchangeOffice);
        $currencyRates = [];
        foreach ($cashboxs as $cashbox) {

            $currencyRate = [];
            $sale = '';
            $purchase = '';
            if (!empty($cashbox->getCurrencyRates()->toArray())) {

                $sale = $cashbox->getCurrencyRates()->last()->getSale();
                $purchase = $cashbox->getCurrencyRates()->last()->getPurchase();
            }
            $currencyRate['sale'] = $sale;
            $currencyRate['purchase'] = $purchase;
            $currencyRate['name'] = $cashbox->getCurrency()->getName();
            $currencyRate['iso'] = $cashbox->getCurrency()->getIso();
            $currencyRate['imageFile'] = $cashbox->getCurrency()->getImageFile();
            $currencyRate['currency'] = $cashbox->getCurrency();
            $currencyRate['cashbox_id'] = $cashbox->getId();
            $currencyRate['default_currency'] = $cashbox->getCurrency()->getDefaultCurrency();
            $currencyRates [] = $currencyRate;

        }
        return new JsonResponse($currencyRates);
    }

    /**
     * @Route("/ajax_currency_rate_create_ajax", name="profile_currency_rate_create_ajax")
     * @Method("POST")
     * @param Request $request
     * @param CashboxRepository $cashboxRepository
     * @param ObjectManager $manager
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @return JsonResponse
     */
    public function createCurrencyRateActionAll(Request $request, CashboxRepository $cashboxRepository, ObjectManager $manager,
                                                ExchangeOfficeRepository $exchangeOfficeRepository)
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = $request->request->all();


        if(!(is_numeric($data['purchase']) && is_numeric($data['sale']))){
            return new JsonResponse([
                'message' => 'Введинны не корректные данные',
                'error' => true
            ]);
        }

        if ($data['cashbox_id']) {
            $exchangeName= $exchangeOfficeRepository->find($data['exchange_id']);
            $currencyRateOne = new CurrencyRate();
            $cashbox = $cashboxRepository->find($data['cashbox_id']);
            if ($cashbox) {
                $currencyRateOne
                    ->setUser($user)
                    ->setCashboxCurrency($cashbox)
                    ->setPurchase($data['purchase'])
                    ->setSale($data['sale']);
                $manager->persist($currencyRateOne);
                $manager->flush();
            }
            return new JsonResponse([
                'message'=>'Ваш курс '. $cashbox->getCurrency()->getName() .' для  обменного пункта '. $exchangeName->getName() .' успешно изменен',
                'error' => false ]);
        }
        if (($data['exchange_id'] === 0 || empty($data['cashbox_id']) )) {

            $exchanges = $exchangeOfficeRepository->findByAllOwnersExchange($user);

            if (!$exchanges) {
                return new JsonResponse('error');
            }
            /** @var ExchangeOffice $exchange */
            foreach ($exchanges as $exchange) {

                $cashboxes = $cashboxRepository->findByCashboxes($exchange['id']);

                if ($cashboxes) {
                    foreach ($cashboxes as $cashbox) {
                        if($cashbox->getCurrency()->getIso() == $data['iso']) {

                            $currencyRate = new CurrencyRate();
                            $currencyRate
                                ->setUser($user)
                                ->setCashboxCurrency($cashbox)
                                ->setPurchase($data['purchase'])
                                ->setSale($data['sale']);
                            $manager->persist($currencyRate);
                            $manager->flush();
                            $cashboxName = $cashbox->getCurrency()->getName();
                        }

                    }
                }
            }

            return new JsonResponse([
                'message'=>"Курс для всех касс  ". $cashboxName . "  был успешно изменен во всех обменных пунктах",
                'error' => false
            ]);
        }
    }

    /**
     * @Route("/change", name="profile_currency_rate_change")
     * @return Response
     */
    public function changeAction()
    {
        return $this->render('profile/currency_rate/change.html.twig');
    }

    /**
     * @Route("/change/exchange_office/{id}", name="profile_currency_rate_change_exchange_office")
     * @param int $id
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @return Response
     */
    public function changeExchangeOfficeAction(
        int $id,
        ExchangeOfficeRepository $exchangeOfficeRepository
    )
    {
        $exchangeOffice = $this->getOwnerExchangeOffice($exchangeOfficeRepository, $id);

        if (!$exchangeOffice) {
            return $this->show404();
        }

        return $this->render('profile/exchange_office/currency_rate.html.twig', [
            'exchangeOffice' => $exchangeOffice
        ]);
    }

}