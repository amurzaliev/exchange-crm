<?php

namespace App\Controller\Profile;

use App\Entity\Cashbox;
use App\Form\CashboxType;
use App\Repository\CashboxRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     * @Route("/", name="profile_cashbox_index")
     * @Method("GET")
     *
     * @param CashboxRepository $cashboxRepository
     * @return Response
     */
    public function indexAction(CashboxRepository $cashboxRepository)
    {
        $cashboxes = $cashboxRepository->findBy([
            'user' => $this->getUser()
        ]);

        return $this->render('profile/cashbox/index.html.twig', [
            'cashboxes' => $cashboxes,
        ]);
    }

    /**
     * @Route("/create", name="profile_cashbox_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param ObjectManager $manager
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

            return $this->redirectToRoute("profile_cashbox_index");
        }

        return $this->render('profile/cashbox/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="profile_cashbox_edit")
     * @Method({"GET", "PATCH"})
     *
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
    ): Response
    {
        $cashbox = $cashboxRepository->findOneBy([
            'id' => $id,
            'user' => $this->getUser()
        ]);

        if (!$cashbox) {
            return $this->render('profile/components/error_messages/404.html.twig');
        }

        $form = $this->createForm(CashboxType::class, $cashbox, ['method' => 'PATCH']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cashbox->setUpdatedAt(new \DateTime());
            $cashbox->setUser($this->getUser());
            $manager->persist($cashbox);
            $manager->flush();

            return $this->redirectToRoute('profile_cashbox_index');
        }

        return $this->render('profile/cashbox/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/detail", name="profile_cashbox_detail")
     * @Method("GET")
     *
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
        return $this->render('profile/cashbox/detail.html.twig', [
                'cashbox' => $cashbox
            ]
        );
    }

}
