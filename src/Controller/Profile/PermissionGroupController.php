<?php

namespace App\Controller\Profile;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class PermissionGroupController
 * @package App\Controller\Profile
 *
 * @Route("permission_group")
 */
class PermissionGroupController extends Controller
{
    /**
     * @Route("/", name="app_profile_permission_group_index")
     */
    public function index()
    {
        return $this->render('permission_group/index.html.twig', [
            'controller_name' => 'PermissionGroupController',
        ]);
    }

    /**
     * @Route("/create", name="app_profile_permission_group_create")
     */
    public function createAction()
    {
        return new Response('createAction');
    }

    /**
     * @Route("/add", name="app_profile_permission_group_add")
     */
    public function addAction()
    {
        return new Response('addAction');
    }

    /**
     * @Route("/{id}/edit", name="app_profile_permission_group_edit")
     * @param int $id
     * @return Response
     */
    public function editAction(int $id)
    {
        return new Response('editAction');
    }
}
