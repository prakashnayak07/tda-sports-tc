<?php

namespace Calendar\View\Helper\Cell\Render;

use Booking\Service\BookingStatusService;
use Zend\View\Helper\AbstractHelper;

class OccupiedForVisitors extends AbstractHelper
{

    protected $bookingStatusService;

    public function __construct(BookingStatusService $bookingStatusService)
    {
        $this->bookingStatusService = $bookingStatusService;
    }

    public function __invoke(array $reservations, array $cellLinkParams, $square, $user = null)
    {
        $view = $this->getView();

        $reservationsCount = count($reservations);

        if ($reservationsCount > 1) {
            return $view->calendarCellLink($this->view->t('Occupied'), $view->url('square', [], $cellLinkParams), 'cc-single');
        } else {
            $reservation = current($reservations);
            $booking = $reservation->needExtra('booking');
            $billingStatus = $booking->getBillingStatus();

            // Normalize billing status to handle inconsistencies
            $normalizedStatus = $this->normalizeBillingStatus($billingStatus);

            // Add billing status class for modern styling
            $billingStatusClass = '';
            if ($normalizedStatus) {
                $billingStatusClass = ' modern-billing-' . $normalizedStatus;
            }

            if ($square->getMeta('public_names', 'false') == 'true') {
                $cellLabel = $booking->needExtra('user')->need('alias');
            } else if ($square->getMeta('private_names', 'false') == 'true' && $user) {
                $cellLabel = $booking->needExtra('user')->need('alias');
            } else {
                $cellLabel = null;
            }

            $cellGroup = ' cc-group-' . $booking->need('bid');

            switch ($booking->need('status')) {
                case 'single':
                    if (! $cellLabel) {
                        $cellLabel = $this->view->t('Occupied');
                    }

                    return $view->calendarCellLink($view->escapeHtml($cellLabel), $view->url('square', [], $cellLinkParams), 'cc-single' . $cellGroup . $billingStatusClass);
                case 'subscription':
                    if (! $cellLabel) {
                        $cellLabel = $this->view->t('Subscription');
                    }

                    return $view->calendarCellLink($view->escapeHtml($cellLabel), $view->url('square', [], $cellLinkParams), 'cc-multiple' . $cellGroup . $billingStatusClass);
            }
        }
    }

    /**
     * Normalize billing status to handle inconsistencies in the database
     */
    protected function normalizeBillingStatus($status)
    {
        if (!$status) {
            return 'pending';
        }

        // Handle common inconsistencies
        $statusMap = [
            'pending_payment' => 'pending',
            'pending' => 'pending',
            'paid' => 'paid',
            'cancelled' => 'cancelled',
            'uncollectable' => 'uncollectable'
        ];

        return $statusMap[$status] ?? 'pending';
    }
}
