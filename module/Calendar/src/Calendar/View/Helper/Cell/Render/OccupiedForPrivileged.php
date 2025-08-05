<?php

namespace Calendar\View\Helper\Cell\Render;

use Booking\Service\BookingStatusService;
use Zend\View\Helper\AbstractHelper;

class OccupiedForPrivileged extends AbstractHelper
{

    protected $bookingStatusService;

    public function __construct(BookingStatusService $bookingStatusService)
    {
        $this->bookingStatusService = $bookingStatusService;
    }

    public function __invoke(array $reservations, array $cellLinkParams)
    {
        $view = $this->getView();

        $reservationsCount = count($reservations);

        if ($reservationsCount > 1) {
            return $view->calendarCellLink($this->view->t('Occupied'), $view->url('backend/booking/edit', [], $cellLinkParams), 'cc-single');
        } else {
            $reservation = current($reservations);
            $booking = $reservation->needExtra('booking');
            $billingStatus = $booking->getBillingStatus();

            // Normalize billing status to handle inconsistencies
            $normalizedStatus = $this->normalizeBillingStatus($billingStatus);

            $bookingStatusColor = $this->bookingStatusService->getStatusColor($normalizedStatus);

            // Add billing status class for modern styling
            $billingStatusClass = '';
            if ($normalizedStatus) {
                $billingStatusClass = ' modern-billing-' . $normalizedStatus;
            }

            if ($bookingStatusColor) {
                $cellStyle = 'outline: solid 3px ' . $bookingStatusColor;
            } else {
                $cellStyle = null;
            }

            $cellLabel = $booking->needExtra('user')->need('alias');
            $cellGroup = ' cc-group-' . $booking->need('bid');

            switch ($booking->need('status')) {
                case 'single':
                    return $view->calendarCellLink($view->escapeHtml($cellLabel), $view->url('backend/booking/edit', [], $cellLinkParams), 'cc-single' . $cellGroup . $billingStatusClass, null, $cellStyle);
                case 'subscription':
                    return $view->calendarCellLink($cellLabel, $view->url('backend/booking/edit', [], $cellLinkParams), 'cc-multiple' . $cellGroup . $billingStatusClass, null, $cellStyle);
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
