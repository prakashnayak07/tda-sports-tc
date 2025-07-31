<?php

namespace Payment\Service;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use RuntimeException;

class StripeService
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        Stripe::setApiKey($this->config['secret_key']);
    }

    /**
     * Create a Stripe Checkout Session for the booking
     *
     * @param array $bookingData
     * @param string $successUrl
     * @param string $cancelUrl
     * @return Session
     */
    public function createCheckoutSession(array $bookingData, string $successUrl, string $cancelUrl)
    {
        $maxRetries = 3;
        $retryDelay = 1; // seconds

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $lineItems = [];
                $totalAmount = 0;

                // Build line items from booking bills
                foreach ($bookingData['bills'] as $bill) {
                    $amount = $bill['price']; // Already in cents from your system
                    $totalAmount += $amount;

                    $lineItems[] = [
                        'price_data' => [
                            'currency' => $this->config['currency'],
                            'product_data' => [
                                'name' => $bill['description'],
                            ],
                            'unit_amount' => $amount,
                        ],
                        'quantity' => $bill['quantity'] ?: 1,
                    ];
                }

                $sessionData = [
                    'payment_method_types' => ['card'],
                    'line_items' => $lineItems,
                    'mode' => 'payment',
                    'success_url' => $successUrl,
                    'cancel_url' => $cancelUrl,
                    'metadata' => [
                        'booking_id' => $bookingData['booking_id'] ?? '',
                        'user_id' => $bookingData['user_id'] ?? '',
                        'square_id' => $bookingData['square_id'] ?? '',
                    ],
                    'customer_email' => $bookingData['customer_email'] ?? null,
                ];

                return Session::create($sessionData);
            } catch (ApiErrorException $e) {
                // Check if it's a network-related error
                if ($this->isNetworkError($e) && $attempt < $maxRetries) {
                    error_log("Stripe connection attempt {$attempt} failed: " . $e->getMessage() . ". Retrying in {$retryDelay} seconds...");
                    sleep($retryDelay);
                    $retryDelay *= 2; // Exponential backoff
                    continue;
                }

                throw new RuntimeException('Stripe session creation failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Check if the error is network-related and worth retrying
     */
    private function isNetworkError(ApiErrorException $e): bool
    {
        $message = $e->getMessage();

        // Network-related error patterns
        $networkErrors = [
            'Could not resolve host',
            'Connection timed out',
            'Network error',
            'SSL connection error',
            'Could not connect to',
            'Connection refused',
        ];

        foreach ($networkErrors as $pattern) {
            if (stripos($message, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Retrieve a Stripe Checkout Session
     *
     * @param string $sessionId
     * @return Session
     */
    public function getCheckoutSession(string $sessionId)
    {
        $maxRetries = 3;
        $retryDelay = 1; // seconds

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                return Session::retrieve($sessionId);
            } catch (ApiErrorException $e) {
                // Check if it's a network-related error
                if ($this->isNetworkError($e) && $attempt < $maxRetries) {
                    error_log("Stripe session retrieval attempt {$attempt} failed: " . $e->getMessage() . ". Retrying in {$retryDelay} seconds...");
                    sleep($retryDelay);
                    $retryDelay *= 2; // Exponential backoff
                    continue;
                }

                throw new RuntimeException('Failed to retrieve Stripe session: ' . $e->getMessage());
            }
        }
    }

    /**
     * Handle webhook events from Stripe
     *
     * @param string $payload
     * @param string $signature
     * @return array|null
     */
    public function handleWebhook(string $payload, string $signature)
    {
        if (empty($this->config['webhook_secret'])) {
            // For development, skip signature verification
            $event = json_decode($payload, true);
        } else {
            try {
                $event = \Stripe\Webhook::constructEvent(
                    $payload,
                    $signature,
                    $this->config['webhook_secret']
                );
            } catch (\Stripe\Exception\SignatureVerificationException $e) {
                throw new RuntimeException('Invalid webhook signature');
            }
        }

        return $event;
    }

    /**
     * Get publishable key for frontend
     *
     * @return string
     */
    public function getPublishableKey()
    {
        return $this->config['publishable_key'];
    }
}
