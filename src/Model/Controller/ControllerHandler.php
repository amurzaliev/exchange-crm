<?php

namespace App\Model\Controller;


use App\Entity\CurrencyRate;
use App\Entity\ExchangeOffice;
use App\Entity\Staff;
use App\Model\ModelHandler;
use App\Repository\CashboxRepository;
use App\Repository\CurrencyRepository;
use App\Repository\ExchangeOfficeRepository;
use App\Repository\StaffRepository;
use App\Repository\VIPClientRepository;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ControllerHandler extends ModelHandler
{
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
        $this->container = $container;
        $this->manager = $manager;
    }


    public function getAllForRoles($repository, User $user){

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $q = $this->manager->getRepository($repository)->findAll();
        } elseif (in_array('ROLE_OWNER', $user->getRoles())) {
            $q = $this->manager->getRepository($repository)->findBy(['user' => $user]);
        } else {
            $q = $this->manager->getRepository($repository)->findBy(['user' => $user->getStaff()->getOwner()]);
        }
        return $q;
    }

    public function getAllStaffForRoles(User $user){

        $staffRepository = $this->manager->getRepository(Staff::class);

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $q = $staffRepository->findAll();
        } elseif (in_array('ROLE_OWNER', $user->getRoles())) {
            $q = $staffRepository->findByAllOwnerStaff($user);
        } else {
            $q = $staffRepository->findByAllOwnerStaff($user->getStaff()->getOwner());
        }
        return $q;
    }


    /**
     * @Route( "//ajax_all_exchange_owner", name="profile_all_owner_exchanges")
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
}