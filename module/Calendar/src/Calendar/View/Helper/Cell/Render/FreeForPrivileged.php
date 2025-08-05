<?php

namespace Calendar\View\Helper\Cell\Render;

use Booking\Service\BookingStatusService;
use Zend\View\Helper\AbstractHelper;

class FreeForPrivileged extends AbstractHelper
{

    protected $bookingStatusService;

    public function __construct(BookingStatusService $bookingStatusService)
    {
        $this->bookingStatusService = $bookingStatusService;
    }

    public function __invoke(array $reservations, array $cellLinkParams, $square)
    {
        $view = $this->getView();

        $reservationsCount = count($reservations);

        if ($reservationsCount == 0) {
            $labelFree = $square->getMeta('label.free', 'Still free');

            return $view->calendarCellLink($labelFree, $view->url('backend/booking/edit', [], $cellLinkParams), 'cc-free cc-free-partially');
        } else if ($reservationsCount == 1) {
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

            $cellLabel = $booking->needExtra('user')->need('alias');
            $cellGroup = ' cc-group-' . $booking->need('bid');

            return $view->calendarCellLink($view->escapeHtml($cellLabel), $view->url('backend/booking/edit', [], $cellLinkParams), 'cc-free cc-free-partially' . $cellGroup . $billingStatusClass);
        } else {
            $labelFree = $square->getMeta('label.free', 'Still free');

            return $view->calendarCellLink($labelFree, $view->url('backend/booking/edit', [], $cellLinkParams), 'cc-free cc-free-partially');
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
