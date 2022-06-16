<?php
namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class PasswordHasherSubscriber implements EventSubscriberInterface{

   
    private $passwordHasher;

    public function __construct(
         UserPasswordHasherInterface $passwordHasher
    ) {
        
        $this->passwordHasher = $passwordHasher;
    }

    public static function getSubscribedEvents()
    {
        return[
            KernelEvents::VIEW=>['hashPassword', EventPriorities::PRE_WRITE]
        ];
    }
    public function hashPassword(ViewEvent $event){
       $user = $event->getControllerResult();
       
       $method = $event->getRequest()->getMethod();
       if($user instanceof User && $method ==="POST"){
        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
       }

    }

}