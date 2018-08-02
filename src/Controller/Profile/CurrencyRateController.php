<?php

namespace App\Controller\Profile;

use App\Entity\Cashbox;
use App\Entity\CurrencyRate;
use App\Entity\User;
use App\Form\CurrencyRateType;
use App\Repository\CurrencyRateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CurrencyRateController
 * @package App\Controller\Profile
 *
 * @Route("profile/currency_rate")
 */
class CurrencyRateController extends Controller
{
    /**
     * @Route("/{id}/show", name="profile_currency_rate_show", requirements={"id"="\d+"})
     * @Method("GET")
     *
     * @param Cashbox $cashbox
     * @param CurrencyRateRepository $currencyRateRepository
     * @return Response
     */
    public function showAction(Cashbox $cashbox, CurrencyRateRepository $currencyRateRepository): Response
    {
        $rates = $cashbox->getCurrencyRates();

        return $this->render('profile/currency_rate/show.html.twig', [
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
    public function createAction(Request $request, EntityManagerInterface $manager, Cashbox $cashbox): Response
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
}