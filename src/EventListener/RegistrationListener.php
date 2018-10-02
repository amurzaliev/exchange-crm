<?php

namespace App\EventListener;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class RegistrationListener implements EventSubscriberInterface
{
    private $router;
    private $requestStack;

    public function __construct(RequestStack $RequestStack, RouterInterface $router)
    {
        $this->requestStack = $RequestStack;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationConfirm'
        ];
    }

    public function onRegistrationConfirm(FilterUserResponseEvent $event)
    {
        /** @var RedirectResponse $response */
        $response = $event->getResponse();
        $response->setTargetUrl($this->router->generate('profile_index'));
    }

}