<?php

namespace Frontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $calendarViewModel = $this->forward()->dispatch('Calendar\Controller\Calendar', ['action' => 'index']);
        $calendarViewModel->setCaptureTo('calendar');

        $dateStart = $calendarViewModel->getVariable('dateStart');
        $dateNow = $calendarViewModel->getVariable('dateNow');
        $squaresFilter = $calendarViewModel->getVariable('squaresFilter');
        $user = $calendarViewModel->getVariable('user');

        $this->redirectBack()->setOrigin('frontend');

        $viewModel = new ViewModel(array(
            'dateStart' => $dateStart,
            'dateNow' => $dateNow,
            'squaresFilter' => $squaresFilter,
            'user' => $user,
        ));

        $viewModel->addChild($calendarViewModel);

        return $viewModel;
    }

    /**
     * Test action to demonstrate 404 page
     * Access via: /test-404
     */
    public function test404Action()
    {
        // This will trigger a 404 error
        throw new \InvalidArgumentException('This is a test 404 page');
    }

    /**
     * Test action to demonstrate custom 404 handling
     * Access via: /not-found
     */
    public function notFoundAction()
    {
        // Simply return the 404 template
        $viewModel = new ViewModel();
        $viewModel->setTemplate('error/404');

        return $viewModel;
    }

    /**
     * Test action to demonstrate working 404 page
     * Access via: /test-404-working
     */
    public function test404WorkingAction()
    {
        // This will show the 404 page without any route errors
        $viewModel = new ViewModel();
        $viewModel->setTemplate('error/404');

        return $viewModel;
    }
}
