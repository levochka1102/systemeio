<?php

namespace App\Payment;

use App\Payment\Exception\PurchaseFailedException;
use App\Payment\Exception\UnknownProcessorException;
use App\Payment\Lib\PaymentProcessor\PaymentProcessor;
use App\Payment\Lib\PaymentProcessorFactory\PaymentProcessorFactory;
use Brick\Money\Money;

class Payment
{
    public function __construct(
        private PaymentProcessorFactory $paymentProcessorFactory
    ) {
    }

    /**
     * @param Money $amount
     * @param PaymentProcessor $paymentProcessor
     * @return true
     * @throws PurchaseFailedException
     * @throws UnknownProcessorException
     */
    public function purchase(Money $amount, string $processor): true
    {
        $processor = $this->paymentProcessorFactory->createProcessor($processor);
        return $processor->purchase($amount);
    }
}
