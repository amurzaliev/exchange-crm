<?php

namespace App\Controller\Profile;

use App\Entity\ExchangeOffice;
use App\Form\ExchangeOfficeType;
use App\Repository\ExchangeOfficeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class PermissionGroupController
 * @package App\Controller\Profile
 *
 * @Route("profile/exchange_office")
 */
class ExchangeOfficeController extends Controller
{
    /**
     * @Route("/", name="app_profile_exchange_office_index")
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @return Response
     */
    public function indexAction(ExchangeOfficeRepository $exchangeOfficeRepository)
    {
        $officeRepository = $exchangeOfficeRepository->findBy([
            'user' => $this->getUser()
        ]);
        return $this->render('profile/exchange_office/index.html.twig',[
            'officeRepositories' => $officeRepository
        ]);
    }

    /**
     * @Route("/create", name="app_profile_exchange_office_create")
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function createAction(Request $request, ObjectManager $manager)
    {
        $officeRepository = new ExchangeOffice();

        $form = $this->createForm(ExchangeOfficeType::class, $officeRepository);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $officeRepository->setUser($this->getUser());
            $manager->persist($officeRepository);
            $manager->flush();

            return $this->redirectToRoute('app_profile_exchange_office_index');
        }

        return $this->render('profile/exchange_office/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_profile_exchange_office_edit")
     * @param Request $request
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @param ObjectManager $manager
     * @param int $id
     * @return Response
     */
    public function editAction(
        Request $request,
        ExchangeOfficeRepository $exchangeOfficeRepository,
        ObjectManager $manager,
        int $id
    )
    {
        $officeRepository = $exchangeOfficeRepository->findOneBy([
            'id' => $id,
            'user' => $this->getUser()
        ]);

        if (!$officeRepository) {
            return $this->render('profile/сomponents/error_messages/404.html.twig');
        }

        $form = $this->createForm(ExchangeOfficeType::class, $officeRepository);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $officeRepository->setUser($this->getUser());
            $manager->persist($officeRepository);
            $manager->flush();

            return $this->redirectToRoute('app_profile_exchange_office_index');
        }

        return $this->render('profile/exchange_office/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/detail", name="app_profile_exchange_office_detail")
     * @param int $id
     * @param ExchangeOfficeRepository $exchangeOfficeRepository
     * @return Response
     */
    public function detailAction(int $id, ExchangeOfficeRepository $exchangeOfficeRepository)
    {
        $officeRepository = $exchangeOfficeRepository->findOneBy([
            'id' => $id,
            'user' => $this->getUser()
        ]);
        return $this->render('profile/exchange_office/detail.html.twig',[
                'officeRepository'=> $officeRepository
            ]
            );
    }
}
