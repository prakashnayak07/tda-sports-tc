<?php

namespace Calendar\View\Helper\Cell\Render;

use Booking\Service\BookingStatusService;
use Zend\View\Helper\AbstractHelper;

class Free extends AbstractHelper
{

    protected $bookingStatusService;

    public function __construct(BookingStatusService $bookingStatusService)
    {
        $this->bookingStatusService = $bookingStatusService;
    }

    public function __invoke($user, $userBooking, array $reservations, array $cellLinkParams, $square)
    {
        $view = $this->getView();

        $labelFree = $square->getMeta('label.free', 'Free');

        if ($user && $user->can('calendar.see-data, calendar.create-single-bookings, calendar.create-subscription-bookings')) {
            return $view->calendarCellRenderFreeForPrivileged($reservations, $cellLinkParams, $square);
        } else if ($user) {
            if ($userBooking) {
                $cellLabel = $view->t('Your Booking');
                $cellGroup = ' cc-group-' . $userBooking->need('bid');

                // Add billing status class for user's own booking
                $billingStatus = $userBooking->getBillingStatus();

                // Normalize billing status to handle inconsistencies
                $normalizedStatus = $this->normalizeBillingStatus($billingStatus);

                $billingStatusClass = '';
                if ($normalizedStatus) {
                    $billingStatusClass = ' modern-billing-' . $normalizedStatus;
                }

                return $view->calendarCellLink($cellLabel, $view->url('square', [], $cellLinkParams), 'cc-own' . $cellGroup . $billingStatusClass);
            } else {
                return $view->calendarCellLink($labelFree, $view->url('square', [], $cellLinkParams), 'cc-free');
            }
        } else {
            return $view->calendarCellLink($labelFree, $view->url('square', [], $cellLinkParams), 'cc-free');
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
