<?php

namespace App\Controller\Profile;

use App\Entity\PermissionGroup;
use App\Form\PermissionGroupType;
use App\Repository\PermissionGroupRepository;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PermissionGroupController
 * @package App\Controller\Profile
 *
 * @Route("profile/permission_group")
 */
class PermissionGroupController extends BaseProfileController
{
    /**
     * @Route("/", name="profile_permission_group_index")
     * @Method("GET")
     *
     * @param PermissionGroupRepository $permissionGroupRepository
     * @return Response
     */
    public function indexAction(PermissionGroupRepository $permissionGroupRepository)
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            $permissionGroups = $permissionGroupRepository->findAll();
        } elseif ($this->isGranted('ROLE_OWNER')) {
            $permissionGroups = $permissionGroupRepository->findBy(['user' => $user]);
        } else {
            $permissionGroups = $permissionGroupRepository->findBy(['user' => $user->getStaff()->getOwner()]);
        }



        return $this->render('profile/permission_group/index.html.twig', [
            'permissionGroups' => $permissionGroups,
        ]);
    }

    /**
     * @Route("/create", name="profile_permission_group_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function createAction(Request $request, ObjectManager $manager)
    {
        $permissionGroup = new PermissionGroup();

        $form = $this->createForm(PermissionGroupType::class, $permissionGroup);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $permissionGroup->setUser($this->getUser());
            $manager->persist($permissionGroup);
            $manager->flush();

            return $this->redirectToRoute('profile_permission_group_index');
        }

        return $this->render('profile/permission_group/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="profile_permission_group_edit")
     * @Method({"GET", "PATCH"})
     *
     * @param Request $request
     * @param PermissionGroupRepository $permissionGroupRepository
     * @param ObjectManager $manager
     * @param int $id
     * @return Response
     */
    public function editAction(
        Request $request,
        PermissionGroupRepository $permissionGroupRepository,
        ObjectManager $manager,
        int $id
    )
    {
        $permissionGroup = $permissionGroupRepository->find($id);

        if (!$permissionGroup) {
            return $this->show404();
        }

        if (!$this->isGranted('EDIT', $permissionGroup)) {
            return $this->show404();
        }

        $form = $this->createForm(PermissionGroupType::class, $permissionGroup, ['method' => 'PATCH']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $permissionGroup->setUser($this->getUser());
            $manager->persist($permissionGroup);
            $manager->flush();

            return $this->redirectToRoute('profile_permission_group_index');
        }

        return $this->render('profile/permission_group/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/detail", name="profile_permission_group_detail")
     * @Method("GET")
     *
     * @param int $id
     * @param PermissionGroupRepository $permissionGroupRepository
     * @return Response
     */
    public function detailAction(int $id, PermissionGroupRepository $permissionGroupRepository)
    {

        $permissionGroup = $permissionGroupRepository->find($id);

        if (!$permissionGroup) {
            return $this->show404();
        }

        if (!$this->isGranted('VIEW', $permissionGroup)) {
            return $this->show404();
        }


        return $this->render('profile/permission_group/detail.html.twig', [
                'permissionGroup' => $permissionGroup
            ]
        );

    }
}
