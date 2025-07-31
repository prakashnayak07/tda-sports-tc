<?php

namespace Square\Controller;

use Booking\Entity\Booking\Bill;
use RuntimeException;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractActionController;

class BookingController extends AbstractActionController
{

    public function customizationAction()
    {
        $dateStartParam = $this->params()->fromQuery('ds');
        $dateEndParam = $this->params()->fromQuery('de');
        $timeStartParam = $this->params()->fromQuery('ts');
        $timeEndParam = $this->params()->fromQuery('te');
        $squareParam = $this->params()->fromQuery('s');

        $serviceManager = @$this->getServiceLocator();
        $squareValidator = $serviceManager->get('Square\Service\SquareValidator');

        $byproducts = $squareValidator->isBookable($dateStartParam, $dateEndParam, $timeStartParam, $timeEndParam, $squareParam);

        $user = $byproducts['user'];

        if (! $user) {
            $query = $this->params()->fromQuery();
            $query['ajax'] = 'false';

            $this->redirectBack()->setOrigin('square/booking/customization', [], ['query' => $query]);

            return $this->redirect()->toRoute('user/login');
        }

        if (! $byproducts['bookable']) {
            throw new RuntimeException(sprintf($this->t('This %s is already occupied'), $this->option('subject.square.type')));
        }

        return $this->ajaxViewModel($byproducts);
    }

    public function confirmationAction()
    {
        $dateStartParam = $this->params()->fromQuery('ds');
        $dateEndParam = $this->params()->fromQuery('de');
        $timeStartParam = $this->params()->fromQuery('ts');
        $timeEndParam = $this->params()->fromQuery('te');
        $squareParam = $this->params()->fromQuery('s');
        $quantityParam = $this->params()->fromQuery('q', 1);
        $productsParam = $this->params()->fromQuery('p', 0);
        $playerNamesParam = $this->params()->fromQuery('pn', 0);

        $serviceManager = @$this->getServiceLocator();
        $squareValidator = $serviceManager->get('Square\Service\SquareValidator');

        $byproducts = $squareValidator->isBookable($dateStartParam, $dateEndParam, $timeStartParam, $timeEndParam, $squareParam);

        $user = $byproducts['user'];

        $query = $this->params()->fromQuery();
        $query['ajax'] = 'false';

        if (! $user) {
            $this->redirectBack()->setOrigin('square/booking/confirmation', [], ['query' => $query]);

            return $this->redirect()->toRoute('user/login');
        } else {
            $byproducts['url'] = $this->url()->fromRoute('square/booking/confirmation', [], ['query' => $query]);
        }

        if (! $byproducts['bookable']) {
            throw new RuntimeException(sprintf($this->t('This %s is already occupied'), $this->option('subject.square.type')));
        }

        /* Check passed quantity */

        if (! (is_numeric($quantityParam) && $quantityParam > 0)) {
            throw new RuntimeException(sprintf($this->t('Invalid %s-amount choosen'), $this->option('subject.square.unit')));
        }

        $square = $byproducts['square'];

        if ($square->need('capacity') - $byproducts['quantity'] < $quantityParam) {
            throw new RuntimeException(sprintf($this->t('Too many %s for this %s choosen'), $this->option('subject.square.unit.plural'), $this->option('subject.square.type')));
        }

        $byproducts['quantityChoosen'] = $quantityParam;

        /* Check passed products */

        $products = array();

        if (! ($productsParam === '0' || $productsParam === 0)) {
            $productManager = $serviceManager->get('Square\Manager\SquareProductManager');
            $productTuples = explode(',', $productsParam);

            foreach ($productTuples as $productTuple) {
                $productTupleParts = explode(':', $productTuple);

                if (count($productTupleParts) != 2) {
                    throw new RuntimeException('Malformed product parameter passed');
                }

                $spid = $productTupleParts[0];
                $amount = $productTupleParts[1];

                if (! (is_numeric($spid) && $spid > 0)) {
                    throw new RuntimeException('Malformed product parameter passed');
                }

                if (! is_numeric($amount)) {
                    throw new RuntimeException('Malformed product parameter passed');
                }

                $product = $productManager->get($spid);

                $productOptions = explode(',', $product->need('options'));

                if (! in_array($amount, $productOptions)) {
                    throw new RuntimeException('Malformed product parameter passed');
                }

                $product->setExtra('amount', $amount);

                $products[$spid] = $product;
            }
        }

        $byproducts['products'] = $products;

        /* Check passed player names */

        if ($playerNamesParam) {
            $playerNames = Json::decode($playerNamesParam, Json::TYPE_ARRAY);

            foreach ($playerNames as $playerName) {
                if (strlen(trim($playerName['value'])) < 5 || ! str_contains(trim($playerName['value']), ' ')) {
                    throw new RuntimeException('Die <b>vollständigen Vor- und Nachnamen</b> der anderen Spieler sind erforderlich');
                }
            }
        } else {
            $playerNames = null;
        }

        /* Check booking form submission */

        $acceptRulesDocument = $this->params()->fromPost('bf-accept-rules-document');
        $acceptRulesText = $this->params()->fromPost('bf-accept-rules-text');
        $confirmationHash = $this->params()->fromPost('bf-confirm');
        $confirmationHashOriginal = sha1('Quick and dirty' . floor(time() / 1800));
        $confirmationHashPrevious = sha1('Quick and dirty' . floor((time() - 1800) / 1800)); // Previous 30-min window

        if ($confirmationHash) {
            // Validate CSRF token to prevent duplicate submissions
            if ($confirmationHash !== $confirmationHashOriginal && $confirmationHash !== $confirmationHashPrevious) {
                $this->flashMessenger()->addErrorMessage($this->t('Invalid or expired form submission. Please try again.'));
                return $this->redirectBack()->toOrigin();
            }

            // Check submission token to prevent duplicate submissions (browser back button)
            $submissionToken = $this->params()->fromPost('bf-submission-token');
            if ($submissionToken) {
                $session = new \Zend\Session\Container('booking_tokens');
                if (!$session->offsetExists($submissionToken)) {
                    $this->flashMessenger()->addErrorMessage($this->t('This form has already been submitted. Please start a new booking.'));
                    return $this->redirectBack()->toOrigin();
                }
                // Remove token after validation to prevent reuse
                $session->offsetUnset($submissionToken);
            }
            if ($square->getMeta('rules.document.file') && $acceptRulesDocument != 'on') {
                $byproducts['message'] = sprintf(
                    $this->t('%sNote:%s Please read and accept the "%s".'),
                    '<b>',
                    '</b>',
                    $square->getMeta('rules.document.name', 'Rules-document')
                );
            }

            if ($square->getMeta('rules.text') && $acceptRulesText != 'on') {
                $byproducts['message'] = sprintf(
                    $this->t('%sNote:%s Please read and accept our rules and notes.'),
                    '<b>',
                    '</b>'
                );
            }

            if ($confirmationHash != $confirmationHashOriginal) {
                $byproducts['message'] = sprintf(
                    $this->t('%We are sorry:%s This did not work somehow. Please try again.'),
                    '<b>',
                    '</b>'
                );
            }

            if (! isset($byproducts['message'])) {

                $bills = array();

                foreach ($products as $product) {
                    $bills[] = new Bill(array(
                        'description' => $product->need('name'),
                        'quantity' => $product->needExtra('amount'),
                        'price' => $product->need('price') * $product->needExtra('amount'),
                        'rate' => $product->need('rate'),
                        'gross' => $product->need('gross'),
                    ));
                }

                if ($square->get('allow_notes')) {
                    $userNotes = "Anmerkungen des Benutzers:\n" . $this->params()->fromPost('bf-user-notes');
                } else {
                    $userNotes = '';
                }

                // Check if payment processing is required
                $optionManager = $serviceManager->get('Base\Manager\OptionManager');
                $paymentOptions = $optionManager->get('service.pricing.payment', 'none');
                $requiresPayment = (strpos($paymentOptions, 'stripe') !== false || strpos($paymentOptions, 'card') !== false);

                $bookingService = $serviceManager->get('Booking\Service\BookingService');

                if ($requiresPayment) {
                    // Create booking with pending payment status
                    $booking = $bookingService->createSingle($user, $square, $quantityParam, $byproducts['dateStart'], $byproducts['dateEnd'], $bills, array(
                        'player-names' => serialize($playerNames),
                        'notes' => $userNotes,
                    ));

                    // Set booking to pending payment status
                    $booking->set('status_billing', 'pending_payment');
                    $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');
                    $bookingManager->save($booking);

                    // Get the complete bills from the booking (includes court pricing + products)
                    $completeBills = $booking->getExtra('bills', array());

                    // Check if there's actually an amount to pay from ALL bills
                    $totalAmount = 0;
                    foreach ($completeBills as $bill) {
                        $totalAmount += $bill->get('price');
                    }

                    if ($totalAmount > 0) {
                        // Prepare booking data for Stripe with ALL bills (court pricing + products)
                        $bookingData = [
                            'booking_id' => $booking->get('bid'),
                            'user_id' => $user->get('uid'),
                            'square_id' => $square->get('sid'),
                            'customer_email' => $user->get('email'),
                            'bills' => []
                        ];

                        foreach ($completeBills as $bill) {
                            $bookingData['bills'][] = [
                                'description' => $bill->get('description'),
                                'quantity' => $bill->get('quantity') ?: 1,
                                'price' => $bill->get('price'),
                                'rate' => $bill->get('rate'),
                                'gross' => $bill->get('gross')
                            ];
                        }

                        // Create Stripe checkout session
                        try {
                            $stripeService = $serviceManager->get('Payment\Service\StripeService');
                            $successUrl = $this->url()->fromRoute('payment/success', [], ['force_canonical' => true]) . '?session_id={CHECKOUT_SESSION_ID}';
                            $cancelUrl = $this->url()->fromRoute('payment/cancel', [], ['force_canonical' => true]) . '?session_id={CHECKOUT_SESSION_ID}';

                            $session = $stripeService->createCheckoutSession($bookingData, $successUrl, $cancelUrl);

                            // Redirect to Stripe checkout
                            return $this->redirect()->toUrl($session->url);
                        } catch (\Exception $e) {
                            // If payment setup fails, cancel the booking
                            $bookingService->cancelSingle($booking);

                            $this->flashMessenger()->addErrorMessage(sprintf($this->t('Payment setup failed: %s'), $e->getMessage()));
                            return $this->redirectBack()->toOrigin();
                        }
                    } else {
                        // No payment required, booking is complete
                        $this->flashMessenger()->addSuccessMessage(sprintf(
                            $this->t('%sCongratulations:%s Your %s has been booked!'),
                            '<b>',
                            '</b>',
                            $this->option('subject.square.type')
                        ));

                        return $this->redirectBack()->toOrigin();
                    }
                } else {
                    // Original flow - no payment processing
                    $bookingService->createSingle($user, $square, $quantityParam, $byproducts['dateStart'], $byproducts['dateEnd'], $bills, array(
                        'player-names' => serialize($playerNames),
                        'notes' => $userNotes,
                    ));

                    $this->flashMessenger()->addSuccessMessage(sprintf(
                        $this->t('%sCongratulations:%s Your %s has been booked!'),
                        '<b>',
                        '</b>',
                        $this->option('subject.square.type')
                    ));

                    return $this->redirectBack()->toOrigin();
                }
            }
        }

        return $this->ajaxViewModel($byproducts);
    }

    public function cancellationAction()
    {
        $bid = $this->params()->fromQuery('bid');

        if (! (is_numeric($bid) && $bid > 0)) {
            throw new RuntimeException('This booking does not exist');
        }

        $serviceManager = @$this->getServiceLocator();
        $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');
        $squareValidator = $serviceManager->get('Square\Service\SquareValidator');

        $booking = $bookingManager->get($bid);

        $cancellable = $squareValidator->isCancellable($booking);

        if (! $cancellable) {
            throw new RuntimeException('This booking cannot be cancelled anymore online.');
        }

        $origin = $this->redirectBack()->getOriginAsUrl();

        /* Check cancellation confirmation */

        $confirmed = $this->params()->fromQuery('confirmed');

        if ($confirmed == 'true') {

            $bookingService = $serviceManager->get('Booking\Service\BookingService');
            $bookingService->cancelSingle($booking);

            $this->flashMessenger()->addSuccessMessage(sprintf(
                $this->t('Your booking has been %scancelled%s.'),
                '<b>',
                '</b>'
            ));

            return $this->redirectBack()->toOrigin();
        }

        return $this->ajaxViewModel(array(
            'bid' => $bid,
            'origin' => $origin,
        ));
    }
}
