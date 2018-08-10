<?php

namespace App\Controller\Profile;

use App\Entity\ExchangeOffice;
use App\Entity\User;
use App\Form\ExchangeOfficeType;
use App\Repository\ExchangeOfficeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @return Response
     */
    public function indexAction(ExchangeOfficeRepository $exchangeOfficeRepository)
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            $exchangeOffices = $exchangeOfficeRepository->findAll();
        } elseif ($this->isGranted('ROLE_OWNER')) {
            $exchangeOffices = $exchangeOfficeRepository->findBy(['user' => $user]);
        } else {
            $exchangeOffices = $user->getStaff()->getExchangeOffices();
        }

        return $this->render('profile/exchange_office/index.html.twig', [
            'exchangeOffices' => $exchangeOffices
        ]);
    }

    /**
     * @Route("/create", name="profile_exchange_office_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function createAction(Request $request, ObjectManager $manager)
    {
        $exchangeOffice = new ExchangeOffice();
        $form = $this->createForm(ExchangeOfficeType::class, $exchangeOffice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $exchangeOffice->setUser($this->getUser());
            $manager->persist($exchangeOffice);
            $manager->flush();

            return $this->redirectToRoute('profile_exchange_office_index');
        }

        return $this->render('profile/exchange_office/create.html.twig', [
            'form' => $form->createView()
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
}
