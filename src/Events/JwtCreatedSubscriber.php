<?php
namespace App\Events;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JwtCreatedSubscriber{

public function updateJwtData(JWTCreatedEvent $event)
{
    $user = $event->getUser();
    $data = $event->getData();
    $data['firstName']= $user->getFirstName();
    $data['lastName']= $user->getlastName();
    $data['email']= $user->getEmail();
    $event->setData($data);
    
}

}