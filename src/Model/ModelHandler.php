<?php

namespace App\Model;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ModelHandler
{
    protected $manager;
    protected $container;

    /**
     * ModelHandler constructor.
     * @param ContainerInterface $container
     * @param ObjectManager $manager
     */
    public function __construct(ContainerInterface $container, ObjectManager $manager)
    {
        $this->container = $container;
        $this->manager = $manager;
    }

    /**
     * @param User $user
     * @return User|null
     */
    public function getOwner(User $user)
    {
        if ($user->getStaff()) {
            return $user->getStaff()->getOwner();
        }

        return $user;
    }
}