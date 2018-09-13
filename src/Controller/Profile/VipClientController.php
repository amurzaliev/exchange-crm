<?php

namespace App\Controller\Profile;

use App\Entity\VIPClient;
use App\Form\VIPClientType;
use App\Repository\VIPClientRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class VipClientController
 * @package App\Controller\Profile
 *
 * @Route("profile/vip_client")
 */
class VipClientController extends BaseProfileController
{
    /**
     * @Route("/", name="profile_vip_client_index")
     * @param VIPClientRepository $VIPClientRepository
     * @return Response
     */
    public function index(VIPClientRepository $VIPClientRepository)
    {
        return $this->render('profile/vip_client/index.html.twig', [
            'vipClients' => $VIPClientRepository->findBy(['user' => $this->getUser()])
        ]);
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @Route("/create", name="profile_vip_client_create")
     * @return Response
     */
    public function createAction(Request $request, ObjectManager $manager)
    {
        $vipClient = new VIPClient();
        $form = $this->createForm(VIPClientType::class, $vipClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $vipClient->setUser($this->getUser());
            $vipClient->setCreatedAt(new \DateTime());
            $manager->persist($vipClient);
            $manager->flush();

            return $this->redirectToRoute("profile_vip_client_index");
        }

        return $this->render('profile/vip_client/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @Route("/create_ajax", name="profile_vip_client_create_ajax")
     * @return JsonResponse
     */
    public function createAjaxAction(Request $request, ObjectManager $manager)
    {
        $message = null;
        $VIPClient = new VIPClient();

        try {
            $fullName = $request->get('fullName');
            $email = $request->get('email');
            $phone = $request->get('phone');

            $VIPClient->setFullName($fullName)
                ->setEmail($email)
                ->setPhone($phone)
            ;

            $VIPClient->setUser($this->getUser());
            $VIPClient->setCreatedAt(new \DateTime());


            $manager->persist($VIPClient);
            $manager->flush();

            $response = [
                'id' => $VIPClient->getId(),
                'fullname' => $VIPClient->getFullName()
            ];

        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return new JsonResponse($response);

    }

    /**
     * @Route("/{id}/edit", name="profile_vip_client_edit")
     * @param Request $request
     * @param VIPClientRepository $VIPClientRepository
     * @param ObjectManager $manager
     * @param int $id
     * @return Response
     */
    public function editAction(
        Request $request,
        VIPClientRepository $VIPClientRepository,
        ObjectManager $manager,
        int $id
    )
    {
        $vipClient = $VIPClientRepository->findOneBy([
            'id' => $id,
            'user' => $this->getUser()
        ]);

        if (!$vipClient) {
            return $this->show404();
        }

        $form = $this->createForm(VIPClientType::class, $vipClient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vipClient->setUpdatedAt(new \DateTime());
            $vipClient->setUser($this->getUser());
            $manager->persist($vipClient);
            $manager->flush();

            return $this->redirectToRoute('profile_vip_client_index');
        }

        return $this->render('profile/vip_client/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/detail", name="profile_vip_client_detail")
     * @param int $id
     * @param VIPClientRepository $VIPClientRepository
     * @return Response
     */
    public function detailAction(int $id, VIPClientRepository $VIPClientRepository)
    {
        $vipClient = $VIPClientRepository->findOneBy([
            'id' => $id,
            'user' => $this->getUser()
        ]);

        if (!$vipClient) {
            return $this->show404();
        }

        return $this->render('profile/vip_client/detail.hml.twig',[
                'vipClient'=> $vipClient
            ]
        );
    }
}
