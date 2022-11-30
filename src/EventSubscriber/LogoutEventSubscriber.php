<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LogoutEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
    public function onLogoutEvent(LogoutEvent $event): void
    {
        //J'ajoute ici le contenu de ma fonction: 
        // un message flash pour dire que j'ai bien été déconnecté si j'avais été dans un controller qui hérite de AbstractController j'aurais pu utiliser la fonction addFlash, ici avec la class LogoutEvent de Symfony il faut passer par getRequest, pour récupérer la session
        // $this->addFlash('success', 'Vous avez bien été déconnecté.');    
        $event->getRequest()->getSession()->getFlashBag()->add(
            'success',
            'Aurevoir '.$event->getToken()->getUser()->getFullName() . '!'
        );
        // Je redirige vers app_home qui est mon accueil général
        // return $this->redirectToRoute('app_home'); ne marche pas non plus, j'utilise  le setResponse de ma class et pour ça je rajoute un __construct en haut de ma Class pour utiliser le generator d'URL de symfony
        $event->setResponse(new RedirectResponse($this->urlGenerator->generate('app_home')));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }
}
