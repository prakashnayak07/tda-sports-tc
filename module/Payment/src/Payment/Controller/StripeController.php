<?php

namespace Payment\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use RuntimeException;

/**
 * @method \Zend\Http\Request getRequest()
 * @method \Zend\Http\Response getResponse()
 */
class StripeController extends AbstractActionController
{
    /**
     * Payment form action
     */
    public function indexAction()
    {
        // This will be called when redirecting to payment
        $sessionId = $this->params()->fromQuery('session_id');

        if (!$sessionId) {
            throw new RuntimeException('Invalid payment session');
        }

        $serviceManager = $this->getServiceLocator();
        $stripeService = $serviceManager->get('Payment\Service\StripeService');

        return new ViewModel([
            'publishableKey' => $stripeService->getPublishableKey(),
            'sessionId' => $sessionId,
        ]);
    }

    /**
     * Process payment creation
     */
    public function processAction()
    {
        if (!$this->getRequest()->isPost()) {
            throw new RuntimeException('Invalid request method');
        }

        $serviceManager = $this->getServiceLocator();
        $stripeService = $serviceManager->get('Payment\Service\StripeService');

        // Get booking data from session or POST
        $bookingData = $this->params()->fromPost('booking_data');

        if (!$bookingData) {
            throw new RuntimeException('No booking data provided');
        }

        // Create Stripe checkout session
        $successUrl = $this->url()->fromRoute('payment/success', [], ['force_canonical' => true]);
        $cancelUrl = $this->url()->fromRoute('payment/cancel', [], ['force_canonical' => true]);

        try {
            $session = $stripeService->createCheckoutSession(
                json_decode($bookingData, true),
                $successUrl,
                $cancelUrl
            );

            return new JsonModel([
                'success' => true,
                'sessionId' => $session->id,
                'url' => $session->url,
            ]);
        } catch (RuntimeException $e) {
            return new JsonModel([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Payment success callback
     */
    public function successAction()
    {
        $sessionId = $this->params()->fromQuery('session_id');

        if ($sessionId) {
            $serviceManager = $this->getServiceLocator();
            $stripeService = $serviceManager->get('Payment\Service\StripeService');

            try {
                $session = $stripeService->getCheckoutSession($sessionId);

                if ($session->payment_status === 'paid') {
                    // If a booking was pre-created, mark it paid; otherwise create it now (deferred mode)
                    $bookingId = $session->metadata->booking_id ?? null;

                    if ($bookingId) {
                        $this->updateBookingPaymentStatus($bookingId, 'paid', $sessionId);
                    } else {
                        $this->createBookingFromSession($session);
                    }

                    $this->flashMessenger()->addSuccessMessage(
                        $this->t('Payment successful! Your booking has been confirmed.')
                    );
                } else {
                    $this->flashMessenger()->addErrorMessage(
                        $this->t('Payment was not completed successfully.')
                    );
                }
            } catch (RuntimeException $e) {
                $this->flashMessenger()->addErrorMessage(
                    $this->t('Error verifying payment: ' . $e->getMessage())
                );
            }
        }

        // Redirect back to origin if available
        $redirectBack = $this->redirectBack();
        if (method_exists($redirectBack, 'toOrigin')) {
            return $redirectBack->toOrigin();
        }
        return $this->redirect()->toRoute('frontend');
    }

    /**
     * Payment cancellation callback
     */
    public function cancelAction()
    {
        $sessionId = $this->params()->fromQuery('session_id');

        error_log("DEBUG - Payment cancelled. Session ID: " . ($sessionId ?: 'NOT PROVIDED'));

        if ($sessionId) {
            $serviceManager = $this->getServiceLocator();
            $stripeService = $serviceManager->get('Payment\Service\StripeService');

            try {
                $session = $stripeService->getCheckoutSession($sessionId);
                $bookingId = $session->metadata->booking_id ?? null;

                error_log("DEBUG - Retrieved session. Booking ID: " . ($bookingId ?: 'NOT FOUND'));
                error_log("DEBUG - Session metadata: " . print_r($session->metadata, true));

                if ($bookingId) {
                    // Cancel the booking since payment was cancelled
                    $this->cancelBookingDueToPaymentFailure($bookingId);

                    $this->flashMessenger()->addInfoMessage(
                        $this->t('Payment was cancelled. Your booking has been cancelled as well.')
                    );
                } else {
                    $this->flashMessenger()->addErrorMessage(
                        $this->t('Payment was cancelled but booking could not be cancelled automatically. Please contact support.')
                    );
                }
            } catch (RuntimeException $e) {
                error_log("DEBUG - Error in cancelAction: " . $e->getMessage());
                $this->flashMessenger()->addErrorMessage(
                    $this->t('Error processing payment cancellation: ' . $e->getMessage())
                );
            }
        } else {
            $this->flashMessenger()->addInfoMessage(
                $this->t('Payment was cancelled. You can try again later.')
            );
        }

        // Always redirect user back to the original origin if possible
        $redirectBack = $this->redirectBack();
        if (method_exists($redirectBack, 'toOrigin')) {
            return $redirectBack->toOrigin();
        }
        return $this->redirect()->toRoute('frontend');
    }

    /**
     * Stripe webhook handler
     */
    public function webhookAction()
    {
        $serviceManager = $this->getServiceLocator();
        $stripeService = $serviceManager->get('Payment\Service\StripeService');

        $payload = $this->getRequest()->getContent();
        $signature = $this->getRequest()->getHeader('Stripe-Signature');

        try {
            $event = $stripeService->handleWebhook($payload, $signature ? $signature->getFieldValue() : '');

            // Handle different event types
            switch ($event['type']) {
                case 'checkout.session.completed':
                    $session = $event['data']['object'];
                    $bookingId = $session['metadata']['booking_id'] ?? null;

                    if ($bookingId && $session['payment_status'] === 'paid') {
                        $this->updateBookingPaymentStatus($bookingId, 'paid', $session['id']);
                    }
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event['data']['object'];
                    // Handle failed payment - cancel the booking
                    $bookingId = $paymentIntent['metadata']['booking_id'] ?? null;
                    if ($bookingId) {
                        $this->cancelBookingDueToPaymentFailure($bookingId);
                    }
                    break;
            }

            return new JsonModel(['status' => 'success']);
        } catch (RuntimeException $e) {
            $this->getResponse()->setStatusCode(400);
            return new JsonModel(['error' => $e->getMessage()]);
        }
    }

    /**
     * Update booking payment status
     */
    private function updateBookingPaymentStatus($bookingId, $status, $transactionId = null)
    {
        $serviceManager = $this->getServiceLocator();
        $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');

        try {
            $booking = $bookingManager->get($bookingId);
            $booking->set('status_billing', $status);

            if ($transactionId) {
                // Store transaction ID in booking meta
                $booking->setMeta('stripe_session_id', $transactionId);
            }

            $bookingManager->save($booking);
        } catch (\Exception $e) {
            // Log error but don't throw to avoid webhook retries
            error_log('Failed to update booking payment status: ' . $e->getMessage());
        }
    }

    /**
     * Create booking record after successful payment (deferred booking model)
     */
    private function createBookingFromSession($session)
    {
        $serviceManager = $this->getServiceLocator();
        $bookingService = $serviceManager->get('Booking\Service\BookingService');
        $squareManager = $serviceManager->get('Square\Manager\SquareManager');
        $userManager = $serviceManager->get('User\Manager\UserManager');

        try {
            $metadata = $session->metadata ?? (object)[];
            $uid = isset($metadata->user_id) ? (int)$metadata->user_id : 0;
            $sid = isset($metadata->square_id) ? (int)$metadata->square_id : 0;
            $ds = $metadata->ds ?? '';
            $de = $metadata->de ?? '';
            $ts = $metadata->ts ?? '';
            $te = $metadata->te ?? '';
            $quantity = isset($metadata->quantity) ? (int)$metadata->quantity : 1;

            if (!($uid && $sid && $ds && $de && $ts && $te)) {
                throw new RuntimeException('Incomplete session metadata to create booking');
            }

            $user = $userManager->get($uid);
            $square = $squareManager->get($sid);

            $dateStart = new \DateTime($ds . ' ' . $ts);
            $dateEnd = new \DateTime($de . ' ' . $te);

            $meta = [];
            if (!empty($metadata->meta)) {
                $decoded = json_decode($metadata->meta, true);
                if (is_array($decoded)) {
                    $meta = $decoded;
                }
            }

            $booking = $bookingService->createSingle($user, $square, $quantity, $dateStart, $dateEnd, [], $meta);
            $this->updateBookingPaymentStatus($booking->need('bid'), 'paid', $session->id);
        } catch (\Exception $e) {
            error_log('Failed to create booking from session: ' . $e->getMessage());
        }
    }

    /**
     * Cancel booking due to payment failure or cancellation
     */
    private function cancelBookingDueToPaymentFailure($bookingId)
    {
        $serviceManager = $this->getServiceLocator();
        $bookingService = $serviceManager->get('Booking\Service\BookingService');

        try {
            $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');
            $booking = $bookingManager->get($bookingId);

            // Cancel the booking
            $bookingService->cancelSingle($booking);

            error_log('Booking ' . $bookingId . ' cancelled due to payment failure/cancellation');
        } catch (\Exception $e) {
            // Log error but don't throw to avoid breaking the user flow
            error_log('Failed to cancel booking due to payment failure: ' . $e->getMessage());
        }
    }
}
