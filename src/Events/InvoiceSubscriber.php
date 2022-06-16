<?php
namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Customer;
use App\Entity\Invoice;
use App\Repository\InvoiceRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class InvoiceSubscriber implements EventSubscriberInterface
{
    private $security;
    private $repository;

    public function __construct(
        Security $security, InvoiceRepository $repository
    ) {
        $this->security = $security;
        $this->repository = $repository;
    }

    public static function getSubscribedEvents()
    {
        return[
            KernelEvents::VIEW=>['setchonoForInvoice', EventPriorities::PRE_VALIDATE]
        ];
    }
    public function getNextChrono(){
        $Chrono = $this->repository->findNextChrono($this->security->getUser());
        $Chrono= intval($Chrono);
        $nextChrono = $Chrono+1;
        return $nextChrono;
    }
    public function setchonoForInvoice(ViewEvent $event){
        $invoice = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ($invoice instanceof Invoice && $method ==="POST") {
            $Chrono =$this->getNextChrono();
            dd($Chrono);
            $invoice->setChrono($Chrono);
            
            if (empty($invoice->getSentAt())) {
                $invoice->setSentAt(new \DateTime());
            }
        }
        }
}