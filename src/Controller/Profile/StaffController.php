<?php

namespace App\Controller\Profile;

use App\Model\Controller\ControllerHandler;
use App\Entity\Staff;
use App\Entity\User;
use App\Form\UserStaffType;
use App\Repository\StaffRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StaffController
 * @package App\Controller\Profile
 *
 * @Route("profile/staff")
 */
class StaffController extends BaseProfileController
{
    /**
     * @Route("/", name="profile_staff_index")
     * @Method("GET")
     *
     * @param ControllerHandler $controllerHandler
     * @return Response
     */
    public function indexAction(ControllerHandler $controllerHandler)
    {
        return $this->render('profile/staff/index.html.twig', [
            'staffs' => $controllerHandler->getAllStaffForRoles($this->getUser()),
        ]);
    }

    /**
     * @Route("/create", name="profile_staff_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function createAction(Request $request, ObjectManager $manager)
    {
        $user = new User();
        $staff = new Staff();

        $form = $this->createForm(UserStaffType::class, [
            'user' => $user,
            'staff' => $staff,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);

            $staff->setUser($user);
            $staff->setOwner($this->getUser());
            $staff->setCreatedAt(new \DateTime());
            $staff->setUpdatedAt(new \DateTime());
            $manager->persist($staff);
            $manager->flush();

            return $this->redirectToRoute('profile_staff_index');
        }

        return $this->render('profile/staff/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="profile_staff_edit", requirements={"id"="\d+"})
     * @Method({"GET", "PATCH"})
     *
     * @param int $id
     * @param Request $request
     * @param ObjectManager $manager
     * @param StaffRepository $staffRepository
     * @return RedirectResponse|Response
     */
    public function editAction(
        int $id,
        Request $request,
        ObjectManager $manager,
        StaffRepository $staffRepository
    )
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $staff = $staffRepository->find($id);
        } elseif ($this->isGranted('ROLE_OWNER')) {
            $staff = $staffRepository->findByOneOwnerStaff($this->getUser(), $id);
        } else {
            $staff = $staffRepository->findByOneOwnerStaff($this->getOwner(), $id);
        }

        if (!$staff) {
            return $this->show404();
        }

        if (!$this->isGranted('EDIT', $staff)) {
            return $this->show404();
        }

        $user = $staff->getUser();

        $form = $this->createForm(UserStaffType::class, [
            'user' => $user,
            'staff' => $staff,
        ], [
            'method' => 'PATCH'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($user);

            $staff->setUpdatedAt(new \DateTime());
            $manager->persist($staff);
            $manager->flush();

            return $this->redirectToRoute('profile_staff_index');
        }

        return $this->render('profile/staff/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/detail", name="profile_staff_detail", requirements={"id"="\d+"})
     * @Method("GET")
     *
     * @param int $id
     * @param StaffRepository $staffRepository
     * @return Response
     */
    public function detailAction(int $id, StaffRepository $staffRepository)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $staff = $staffRepository->find($id);
        } elseif ($this->isGranted('ROLE_OWNER')) {
            $staff = $staffRepository->findByOneOwnerStaff($this->getUser(), $id);
        } else {
            $staff = $staffRepository->findByOneOwnerStaff($this->getOwner(), $id);
        }

        if (!$staff) {
            return $this->show404();
        }

        if (!$this->isGranted('VIEW', $staff)) {
            return $this->show404();
        }

        return $this->render('profile/staff/detail.html.twig', [
            'staff' => $staff
        ]);
    }
}
