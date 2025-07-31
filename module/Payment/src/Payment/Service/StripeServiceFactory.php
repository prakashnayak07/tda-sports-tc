<?php

namespace Payment\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StripeServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $stripeConfig = $config['stripe'] ?? [];

        return new StripeService($stripeConfig);
    }
}
