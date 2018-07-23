<?php

namespace App\Controller\Profile;

use App\Entity\PermissionGroup;
use App\Form\PermissionGroupType;
use App\Repository\PermissionGroupRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class PermissionGroupController
 * @package App\Controller\Profile
 *
 * @Route("profile/permission_group")
 */
class PermissionGroupController extends Controller
{
    /**
     * @Route("/", name="app_profile_permission_group_index")
     * @param PermissionGroupRepository $permissionGroupRepository
     * @return Response
     */
    public function index(PermissionGroupRepository $permissionGroupRepository)
    {
        $permissionGroups = $permissionGroupRepository->findAll();
        return $this->render('profile/permission_group/index.html.twig', [
            'permissionGroups' => $permissionGroups,
        ]);
    }

    /**
     * @Route("/create", name="app_profile_permission_group_create")
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

            return $this->redirectToRoute('app_profile_permission_group_index');
        }

        return $this->render('profile/permission_group/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_profile_permission_group_edit")
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
        $permissionGroup = $permissionGroupRepository->findOneBy([
            'id' => $id,
            'user' => $this->getUser()
        ]);

        $form = $this->createForm(PermissionGroupType::class, $permissionGroup);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $permissionGroup->setUser($this->getUser());
            $manager->persist($permissionGroup);
            $manager->flush();

            return $this->redirectToRoute('app_profile_permission_group_index');
        }

        return $this->render('profile/permission_group/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
