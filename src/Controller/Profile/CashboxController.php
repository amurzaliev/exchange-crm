<?php

namespace App\Controller\Profile;

use App\Model\Controller\ControllerHandler;
use App\Entity\Cashbox;
use App\Form\CashboxType;
use App\Repository\CashboxRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * Class CashboxController
 * @package App\Controller\Profile
 *
 * @Route("profile/cashbox")
 */
class CashboxController extends BaseProfileController
{
    /**
     * @Route("/", name="profile_cashbox_index")
     * @Method("GET")
     *
     * @param ControllerHandler $controllerHandler
     * @return Response
     */
    public function indexAction(ControllerHandler $controllerHandler)
    {
        return $this->render('profile/cashbox/index.html.twig', [
            'cashboxes' => $controllerHandler->getAllForRoles(Cashbox::class, $this->getUser()),
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
        $cashbox = $cashboxRepository->find($id);

        if (!$cashbox) {
            return $this->show404();
        }

        if (!$this->isGranted('EDIT', $cashbox)) {
            return $this->show404();
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
            'id' => $id
        ]);
        if (!$cashbox) {
            return $this->show404();
        }

        if (!$this->isGranted('VIEW', $cashbox)) {
            return $this->show404();
        }

        return $this->render('profile/cashbox/detail.html.twig', [
                'cashbox' => $cashbox
            ]
        );
    }

}
