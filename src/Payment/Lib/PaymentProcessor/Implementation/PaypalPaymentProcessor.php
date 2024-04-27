<?php

namespace App\Payment\Lib\PaymentProcessor\Implementation;

use App\Payment\Exception\PurchaseFailedException;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor as SystemeioPaypalPaymentProcessor;
use App\Payment\Lib\PaymentProcessor\PaymentProcessor;
use Brick\Money\Money;
use Exception;


class PaypalPaymentProcessor implements PaymentProcessor
{
    public function __construct(
        private SystemeioPaypalPaymentProcessor $paymentProcessor
    ) {
    }

    /**
     * @inheritDoc
     */
    public function purchase(Money $amount): true
    {
        try {
            $this->paymentProcessor->pay($amount->getMinorAmount()->toInt());
        } catch (Exception $e) {
            throw new PurchaseFailedException($e->getMessage(), previous: $e);
        }

        return true;
    }
}
