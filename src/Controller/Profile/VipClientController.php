<?php

namespace App\Controller\Profile;

use App\Entity\Currency;
use App\Entity\VIPClient;
use App\Form\VIPClientType;
use App\Repository\VIPClientRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class VipClientController
 * @package App\Controller\Profile
 *
 * @Route("profile/vip_client")
 */
class VipClientController extends Controller
{
    /**
     * @Route("/", name="profile_vip_client_index")
     * @param VIPClientRepository $VIPClientRepository
     * @return Response
     */
    public function index(VIPClientRepository $VIPClientRepository)
    {
        $vipClients = $VIPClientRepository->findBy([
            'user' => $this->getUser()
        ]);
        return $this->render('profile/vip_client/index.html.twig', [
            'vipClients' => $vipClients,
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
            return $this->render('profile/сomponents/error_messages/404.html.twig');
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
        return $this->render('profile/vip_client/detail.hml.twig',[
                'vipClient'=> $vipClient
            ]
        );
    }
}
