<?php

namespace App\Controller\Profile;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class StaffController
 * @package App\Controller\Profile
 *
 */
abstract class BaseProfileController extends Controller
{

    public function show404()
    {
        return $this->render('profile/сomponents/error_messages/404.html.twig');
    }
}