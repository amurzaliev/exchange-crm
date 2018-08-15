<?php

namespace App\Controller\Profile;

use App\Entity\User;
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

}