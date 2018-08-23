<?php

namespace App\Controller\Profile;

use App\Model\Controller\ControllerHandler;
use App\Entity\Currency;
use App\Form\CurrencyType;
use App\Repository\CurrencyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CurrencyController
 * @package App\Controller\Profile
 *
 * @Route("profile/currency")
 */
class CurrencyController extends BaseProfileController
{
    /**
     * @Route("/", name="profile_currency_index")
     * @Method("GET")
     *
     * @param ControllerHandler $controllerHandler
     * @return Response
     */
    public function indexAction(ControllerHandler $controllerHandler)
    {
        return $this->render('profile/currency/index.html.twig', [
            'currency' => $controllerHandler->getAllForRoles(Currency::class, $this->getUser()),
        ]);
    }

    /**
     * @Route("/create", name="profile_currency_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function createAction(Request $request, ObjectManager $manager)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->show404();
        }

        $currency = new Currency();

        $form = $this->createForm(CurrencyType::class, $currency);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currency->setUser($this->getUser());
            $manager->persist($currency);
            $manager->flush();

            return $this->redirectToRoute('profile_currency_index');
        }

        return $this->render('profile/currency/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="profile_currency_edit")
     * @Method({"GET", "PATCH"})
     *
     * @param Request $request
     * @param CurrencyRepository $currencyRepository
     * @param ObjectManager $manager
     * @param int $id
     * @return Response
     */
    public function editAction(
        Request $request,
        CurrencyRepository $currencyRepository,
        ObjectManager $manager,
        int $id
    ): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->show404();
        }

        $currencies = $currencyRepository->find($id);

        if (!$currencies) {
            return $this->show404();
        }
        $form = $this->createForm(CurrencyType::class, $currencies, ['method' => 'PATCH']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currencies->setUser($this->getUser());
            $manager->persist($currencies);
            $manager->flush();

            return $this->redirectToRoute('profile_currency_index');
        }

        return $this->render('profile/currency/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/detail", name="profile_currency_detail")
     * @Method("GET")
     *
     * @param int $id
     * @param CurrencyRepository $currencyRepository
     * @return Response
     */
    public function detailAction(int $id, CurrencyRepository $currencyRepository)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->show404();
        }

        $currency = $currencyRepository->find($id);

        if (!$currency) {
            return $this->show404();
        }

        return $this->render('profile/currency/detail.html.twig', [
                'currency' => $currency
            ]
        );
    }
}
