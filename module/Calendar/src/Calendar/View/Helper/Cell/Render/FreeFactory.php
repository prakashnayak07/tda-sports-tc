<?php

namespace Calendar\View\Helper\Cell\Render;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FreeFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sm)
    {
        return new Free($sm->getServiceLocator()->get('Booking\Service\BookingStatusService'));
    }
}
