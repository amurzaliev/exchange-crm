<?php

namespace App\Controller\Profile;

use App\Entity\Currency;
use App\Form\CurrencyType;
use App\Repository\CurrencyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CurrencyController
 * @package App\Controller\Profile
 *
 * @Route("profile/currency")
 */
class CurrencyController extends Controller
{
    /**
     * @Route("/", name="profile_currency_index")
     * @Method("GET")
     *
     * @param CurrencyRepository $currencyRepository
     * @return Response
     */
    public function indexAction(CurrencyRepository $currencyRepository)
    {
        $currencies = $currencyRepository->findBy([
            'user' => $this->getUser()
        ]);
        return $this->render('profile/currency/index.html.twig', [
            'currencies' => $currencies
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
        $currencies = $currencyRepository->findOneBy([
            'id' => $id,
            'user' => $this->getUser()
        ]);

        if (!$currencies) {
            return $this->render('profile/components/error_messages/404.html.twig');
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
        $currency = $currencyRepository->findOneBy([
            'id' => $id,
            'user' => $this->getUser()
        ]);
        return $this->render('profile/currency/detail.html.twig', [
                'currency' => $currency
            ]
        );
    }
}
