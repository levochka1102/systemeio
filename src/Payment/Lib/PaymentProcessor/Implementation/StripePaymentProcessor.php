<?php

namespace App\Payment\Lib\PaymentProcessor\Implementation;

use App\Payment\Exception\PurchaseFailedException;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor as SystemeioStripePaymentProcessor;
use App\Payment\Lib\PaymentProcessor\PaymentProcessor;
use Brick\Money\Money;

class StripePaymentProcessor implements PaymentProcessor
{
    public function __construct(
        private SystemeioStripePaymentProcessor $paymentProcessor
    ) {
    }

    /**
     * @inheritDoc
     */
    public function purchase(Money $amount): true
    {
        $result = $this->paymentProcessor->processPayment($amount->getAmount()->toFloat());

        if (!$result) {
            throw new PurchaseFailedException('Payment failed');
        }

        return true;
    }
}
