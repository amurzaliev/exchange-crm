<?php

namespace App\Controller\Profile;

use App\Entity\Staff;
use App\Repository\StaffRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class StaffController
 * @package App\Controller\Profile
 *
 * @Route("profile/staff")
 */
class StaffController extends Controller
{

    /**
     * @Route("/", name="profile_staff_index")
     * @Method({"GET"})
     * @param StaffRepository $staffRepository
     * @return Response
     */
    public function indexAction(StaffRepository $staffRepository)
    {
        $staffs = $staffRepository->findByAllOwnerStaff($this->getUser());

        return $this->render('profile/staff/index.html.twig', [
            'staffs' => $staffs
        ]);
    }

    /**
     * @Route("/create", name="profile_staff_create")
     * @return Response
     * @Method({"GET", "POST"})
     */
    public function createAction()
    {
        return $this->render('profile/staff/create.html.twig');
    }

    /**
     * @Route("/{id}/edit", name="profile_staff_edit", requirements={"id"="\d+"})
     * @Method({"GET", "PUT"})
     * @param int $id
     * @return Response
     */
    public function editAction(int $id)
    {
        return $this->render('profile/staff/edit.html.twig');
    }

    /**
     * @Route("/{id}/show", name="profile_staff_show", requirements={"id"="\d+"})
     * @param int $id
     * @Method({"GET"})
     * @return Response
     */
    public function showAction(int $id)
    {
        return $this->render('profile/staff/show.html.twig');
    }
}
