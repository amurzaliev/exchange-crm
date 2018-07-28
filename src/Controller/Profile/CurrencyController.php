<?php

namespace App\Controller\Profile;

use App\Entity\Currency;
use App\Form\CurrencyType;
use App\Repository\CurrencyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/", name="app_profile_currency_index")
     * @param CurrencyRepository $currencyRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(CurrencyRepository $currencyRepository )
    {
        $currencies = $currencyRepository->findBy([
            'user' => $this->getUser()
        ]);
        return $this->render('profile/currency/index.html.twig',[
            'currencies'=> $currencies
        ]);
    }

    /**
     * @Route("/create", name="app_profile_currency_create")
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request, ObjectManager $manager)
    {
        $currency = new Currency();

        $form = $this->createForm(CurrencyType::class, $currency);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $currency->setUser($this->getUser());
            $currency->setCreateAt(new \DateTime('now'));
            $manager->persist($currency);
            $manager->flush();

            return $this->redirectToRoute('app_profile_currency_index');
        }

        return $this->render('profile/currency/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
