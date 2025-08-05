<?php

namespace Calendar\View\Helper\Cell\Render;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FreeForPrivilegedFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sm)
    {
        return new FreeForPrivileged($sm->getServiceLocator()->get('Booking\Service\BookingStatusService'));
    }
}
