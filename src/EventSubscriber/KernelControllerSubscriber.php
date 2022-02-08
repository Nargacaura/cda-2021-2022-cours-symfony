<?php

namespace App\EventSubscriber;

use App\Repository\TagRepository;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

class KernelControllerSubscriber implements EventSubscriberInterface
{
    private $tagRepo, $twig;
    public function __construct(TagRepository $tagRepo, Environment $twig)
    {
        $this->tagRepo = $tagRepo;
        $this->twig = $twig;
    }
    public function onKernelController(ControllerEvent $event)
    {
        $tags = $this->tagRepo->findAll();
        
        // Quand Twig veut ses tags...
        $this->twig->addGlobal("global_tags", $tags);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
