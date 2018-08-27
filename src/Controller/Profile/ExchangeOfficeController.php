<?php

namespace App\Controller\Profile;

use App\Model\Controller\ControllerHandler;
use App\Entity\Cashbox;
use App\Entity\ExchangeOffice;
use App\Form\ExchangeOfficeType;
use App\Repository\CashboxRepository;
use App\Repository\CurrencyRepository;
use App\Repository\ExchangeOfficeRepository;
use App\Repository\StaffRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PermissionGroupController
 * @package App\Controller\Profile
 *
 * @Route("profile/exchange_office")
 */
class ExchangeOfficeController extends BaseProfileController
{
    /**
     * @Route("/", name="profile_exchange_office_index")
     * @Method("GET")
     *
     * @param ControllerHandler $controllerHandler
     * @return Response
     */
    public function indexAction(ControllerHandler $controllerHandler)
    {
        return $this->render('profile/exchange_office/index.html.twig', [
            'exchangeOffices' => $controllerHandler->getAllForRoles(ExchangeOffice::class, $this->getUser()),
        ]);
    }

    /**
     * @Route("/create", name="profile_exchange_office_create")
     * @Method({"GET", "POST"})
     * @param StaffRepository $staffRepository
     * @param CurrencyRepository $currencyRepository
     * @return Response
     */
    public function createAction(StaffRepository $staffRepository, CurrencyRepository $currencyRepository)
    {

        $staffs = $staffRepository->findByAllOwnerStaff($this->getUser());

        $currencies = $currencyRepository->findAll();


        return $this->render('profile/exchange_office/create.html.twig', [
            'staffs' => $staffs,
            'currencies' => $currencies
        ]);
    }

    /**
     * @Route("/{id}/edit", name="profile_exchange_office_edit")
     * @Method({"GET", "PATCH"})
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @param int $id
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @return Response
     */
    public function editAction(
        Request $request,
        ObjectManager $manager,
        int $id,
        ExchangeOfficeRepository $exchangeOfficeRepository
    )
    {
        $exchangeOffice = $exchangeOfficeRepository->find($id);
        if (!$exchangeOffice) {
            return $this->show404();
        }

        if (!$this->isGranted('EDIT', $exchangeOffice)) {
            return $this->show404();
        }

        $form = $this->createForm(ExchangeOfficeType::class, $exchangeOffice, ['method' => 'PATCH']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $exchangeOffice->setUser($this->getUser());
            $manager->persist($exchangeOffice);
            $manager->flush();

            return $this->redirectToRoute('profile_exchange_office_index');
        }

        return $this->render('profile/exchange_office/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/detail", name="profile_exchange_office_detail")
     * @Method("GET")
     *
     * @param int $id
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @return Response
     */
    public function detailAction(int $id, ExchangeOfficeRepository $exchangeOfficeRepository)
    {
        $exchangeOffice = $exchangeOfficeRepository->find($id);

        if (!$exchangeOffice) {
            return $this->show404();
        }

        if (!$this->isGranted('VIEW', $exchangeOffice)) {
            return $this->show404();
        }

        return $this->render('profile/exchange_office/detail.html.twig', [
                'officeRepository' => $exchangeOffice
            ]
        );

    }

    /**
     * @Route("/create_exchange_office_ajax", name="profile_exchange_office_create_ajax")
     * @Method("POST")
     * @param Request $request
     * @param ObjectManager $manager
     * @param CurrencyRepository $currencyRepository
     * @param StaffRepository $staffRepository
     * @return JsonResponse
     */
    public function createActionAjax(Request $request, ObjectManager $manager,
                                     CurrencyRepository $currencyRepository,
                                     StaffRepository $staffRepository
    )
    {
        $data = $request->request->all();
        $message = '';
        $exchangeOfficeId = null;

        try {
            if (empty($data['name'])) {

                throw new \Exception('Поле название обменного пункта не может быть пустым');
            }


            $exchangeOffice = new ExchangeOffice();
            $exchangeOffice->setUser($this->getUser());
            $exchangeOffice->setName($data['name']);
            $exchangeOffice->setAddress($data['address']);
            $exchangeOffice->setContact($data['contact']);
            $exchangeOffice->setActive($data['active']);
            $manager->persist($exchangeOffice);

            $cashbox = new Cashbox();
            $cashbox->setUser($this->getUser());
            $currency = $currencyRepository->findByOneIso($this->getParameter('default.currency'));
            $cashbox->setCurrency($currency);
            $cashbox->setExchangeOffice($exchangeOffice);
            $manager->persist($cashbox);

            $cashboxIds =[];

            switch (array_key_exists('staffs',$data)){
                case  true :
                    $staffs = $data['staffs'];
                    foreach ($staffs as $staffId)
                    {
                        $staff = $staffRepository->findByOneOwnerStaff($this->getUser(), (int)$staffId);
                        $exchange = $exchangeOffice->addStaff($staff);
                        $manager->persist($exchange);
                    }
            }
            switch (array_key_exists('cashboxes', $data)){
                case  true :
                    $cashboxes = $data['cashboxes'];
                    foreach ($cashboxes as $cashboxId)
                    {
                        $cashboxChange = new Cashbox();
                        $currency = $currencyRepository->find($cashboxId);
                        $cashboxChange->setUser($this->getUser());
                        $cashboxChange->setCurrency($currency);
                        $cashboxChange->setExchangeOffice($exchangeOffice);
                        $manager->persist($cashboxChange);
                        $cashboxIds[] = $cashboxChange->getId();
                    }

            }
            $manager->flush();

//            $exchangeOfficeId = $exchangeOffice->getId();

        } catch (\Exception $e) {
            $message = $e->getMessage();
        }


        return new JsonResponse([
//            'url' =>  $exchangeOfficeId,
            'message' => $message
        ]);
    }
}
