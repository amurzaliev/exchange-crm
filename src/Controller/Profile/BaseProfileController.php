<?php

namespace App\Controller\Profile;

use App\Entity\User;
use App\Repository\ExchangeOfficeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class BaseProfileController
 * @package App\Controller\Profile
 *
 */
abstract class BaseProfileController extends Controller
{

    public function show404()
    {
        return $this->render('profile/components/error_messages/404.html.twig');
    }

    /**
     * @return User|null
     */
    public function getOwner()
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->getStaff()) {
            return $user->getStaff()->getOwner();
        }

        return $user;
    }

    public function getOwnerExchangeOffice(ExchangeOfficeRepository $exchangeOfficeRepository, $id)
    {
        $owner = null;
        $staff = null;

        if (!$this->isGranted('ROLE_ADMIN')) {
            $owner = $this->getOwner();
        }

        if ($this->isGranted('ROLE_USER')) {
            $staff = $this->getUser()->getStaff();
        }

        $exchangeOffice = $exchangeOfficeRepository->findByOne($id, $owner, $staff);

        return $exchangeOffice;
    }

}