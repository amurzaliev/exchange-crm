<?php

namespace App\Controller\Profile;

use App\Entity\Cashbox;
use App\Form\CashboxType;
use App\Repository\CashboxRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CashboxController
 * @package App\Controller\Profile
 *
 * @Route("profile/cashbox")
 */
class CashboxController extends Controller
{
    /**
     * @Route("/", name="app_profile_cashbox_index")
     * @param CashboxRepository $cashboxRepository
     * @return Response
     */
    public function index(CashboxRepository $cashboxRepository)
    {
        $cashboxes = $cashboxRepository->findBy([
            'user' => $this->getUser()
        ]);

        return $this->render('profile/cashbox/index.html.twig', [
            'cashboxes' => $cashboxes,
        ]);
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @Route("/create", name="app_profile_cashbox_create")
     * @return Response
     */
    public function createAction(Request $request, ObjectManager $manager)
    {
        $cashbox = new Cashbox();
        $form = $this->createForm(CashboxType::class, $cashbox);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cashbox->setUser($this->getUser());
            $manager->persist($cashbox);
            $manager->flush();

            return $this->redirectToRoute("app_profile_cashbox_index");
        }

        return $this->render('profile/cashbox/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_profile_cashbox_edit")
     * @param Request $request
     * @param CashboxRepository $cashboxRepository
     * @param ObjectManager $manager
     * @param int $id
     * @return Response
     */
    public function editAction(
        Request $request,
        CashboxRepository $cashboxRepository,
        ObjectManager $manager,
        int $id
    )
    {
        $cashRepository = $cashboxRepository->findOneBy([
            'id' => $id,
            'user' => $this->getUser()
        ]);

        if (!$cashRepository) {
            return $this->render('profile/сomponents/error_messages/404.html.twig');
        }

        $form = $this->createForm(CashboxType::class, $cashRepository);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cashRepository->setUpdatedAt(new \DateTime());
            $cashRepository->setUser($this->getUser());
            $manager->persist($cashRepository);
            $manager->flush();

            return $this->redirectToRoute('app_profile_cashbox_index');
        }

        return $this->render('profile/cashbox/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/detail", name="app_profile_cashbox_detail")
     * @param int $id
     * @param CashboxRepository $cashboxRepository
     * @return Response
     */
    public function detailAction(int $id, CashboxRepository $cashboxRepository)
    {
        $cashbox = $cashboxRepository->findOneBy([
            'id' => $id,
            'user' => $this->getUser()
        ]);
        return $this->render('profile/cashbox/detail.html.twig',[
                'cashbox'=> $cashbox
            ]
        );
    }

}
