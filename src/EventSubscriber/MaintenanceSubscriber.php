<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpFoundation\Cookie;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    public function onKernelResponse(ResponseEvent $event)
    {
        // $event contient l'objet Response qui s'apprête à être exécuté
        // On peut modifier la Response comme on veut
        // Ajoutons lui qu'on veut set un cookie !
        $event->getResponse()->headers->setCookie(new Cookie('saveur', 'pépites de chocolat'));
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}
