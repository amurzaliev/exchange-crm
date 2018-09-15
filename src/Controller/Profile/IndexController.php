<?php

namespace App\Controller\Profile;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/profile/", name="profile_index")
     */
    public function indexAction()
    {
        return $this->render('profile/index/index.html.twig');
    }
}
