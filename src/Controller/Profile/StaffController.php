<?php

namespace App\Controller\Profile;

use App\Entity\Staff;
use App\Entity\User;
use App\Form\UserStaffType;
use App\Repository\ExchangeOfficeRepository;
use App\Repository\PermissionGroupRepository;
use App\Repository\StaffRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @param StaffRepository $staffRepository
     * @param PermissionGroupRepository $permissionGroupRepository
     * @return Response
     */
    public function indexAction(
        StaffRepository $staffRepository,
        PermissionGroupRepository $permissionGroupRepository
    )
    {
        /** @var User $user */
        $user = $this->getUser();

        $permissionGroups = $permissionGroupRepository->findAllByOwner($user);

        if ($this->isGranted('ROLE_ADMIN')) {
            $staffs = $staffRepository->findAll();
        } elseif ($this->isGranted('ROLE_OWNER')) {
            $staffs = $staffRepository->findByAllOwnerStaff($this->getUser());
        } else {
            $staffs = $staffRepository->findByAllOwnerStaff($user->getStaff()->getOwner());
        }

        return $this->render('profile/staff/index.html.twig', [
            'staffs' => $staffs,
            'permissionGroups' => $permissionGroups
        ]);
    }

    /**
     * @Route("/create-ajax", name="profile_staff_create_ajax")
     * @Method("POST")
     * @param Request $request
     * @param ObjectManager $manager
     * @param PermissionGroupRepository $permissionGroupRepository
     * @param StaffRepository $staffRepository
     * @return JsonResponse
     */
    public function createAjaxAction(
        Request $request,
        ObjectManager $manager,
        PermissionGroupRepository $permissionGroupRepository,
        StaffRepository $staffRepository
    )
    {
        $message = null;
        $blockList = null;
        $user = new User();
        $staff = new Staff();

        try {
            $fullname = $request->get('fullname');
            $username = $request->get('username');
            $password = $request->get('password');
            $enabled = $request->get('enabled');
            $group = intval($request->get('group'));
            $position = $request->get('position');
            $permissionGroup = $permissionGroupRepository->find($group);

            $user->setFullName($fullname)
                ->setUsername($username)
                ->setPlainPassword($password)
                ->setEnabled($enabled)
            ;

            $manager->persist($user);

            $staff->setUser($user)
                ->setOwner($this->getOwner())
                ->setPermissionGroup($permissionGroup)
                ->setPosition($position)
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
            ;

            $manager->persist($staff);
            $manager->flush();

            $blockList = $this->render('profile/staff/ajax/list_block.html.twig', [
                'staff' => $staffRepository->find($staff->getId())
            ])->getContent();

            $staffData = [
                'id' => $staff->getId(),
                'fullname' => $staff->getUser()->getFullName()
            ];

        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return new JsonResponse([
            "message" => $message,
            'staffData' => $staffData,
            'blockList' => $blockList
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

    /**
     * @Route("/get_data", name="profile_staff_get_data")
     * @param Request $request
     * @param StaffRepository $staffRepository
     * @return RedirectResponse|Response
     */
    public function getDataAction(Request $request, StaffRepository $staffRepository)
    {
        $id = $request->get('stafId');

        if ($this->isGranted('ROLE_ADMIN')) {
            $staff = $staffRepository->find($id);
        } elseif ($this->isGranted('ROLE_OWNER')) {
            $staff = $staffRepository->findByOneOwnerStaff($this->getUser(), $id);
        } else {
            $staff = $staffRepository->findByOneOwnerStaff($this->getOwner(), $id);
        }

        return new JsonResponse([
            "message" => '',
            'data' => $staff->toArray()
        ]);
    }

    /**
     * @Route("/edit-ajax", name="profile_staff_edit_ajax")
     * @Method("POST")
     * @param Request $request
     * @param ObjectManager $manager
     * @param PermissionGroupRepository $permissionGroupRepository
     * @param StaffRepository $staffRepository
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function editAjaxAction(
        Request $request,
        ObjectManager $manager,
        PermissionGroupRepository $permissionGroupRepository,
        StaffRepository $staffRepository,
        UserRepository $userRepository
    )
    {
        $message = null;
        $blockList = null;
        $updatedAt = new \DateTime();

        try {
            $fullname = $request->get('fullname');
            $username = $request->get('username');
            $password = $request->get('password');
            $enabled = $request->get('enabled');
            $group = intval($request->get('group'));
            $position = $request->get('position');
            $stafId = intval($request->get('stafId'));

            $staff = $staffRepository->find($stafId);
            $user = $staff->getUser();
            $permissionGroup = $permissionGroupRepository->find($group);

            $user->setFullName($fullname)
                ->setUsername($username)
                ->setEnabled($enabled)
            ;

            if (!empty($password)) {
                $user->setPlainPassword($password);
            }

            $manager->persist($user);

            $staff->setUser($user)
                ->setOwner($this->getOwner())
                ->setPermissionGroup($permissionGroup)
                ->setPosition($position)
                ->setUpdatedAt($updatedAt)
            ;

            $manager->persist($staff);
            $manager->flush();

        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return new JsonResponse([
            "message" => $message,
            'updatedAt' => $updatedAt->format("d-m-Y H:i:s")
        ]);
    }
}
