<?php

namespace App\Payment\Lib\PaymentProcessorFactory;

use App\Payment\Exception\UnknownProcessorException;
use App\Payment\Lib\PaymentProcessor\PaymentProcessor;

interface PaymentProcessorFactory
{
    /**
     * @param string $processor
     * @return PaymentProcessor
     * @throws UnknownProcessorException
     */
    public function createProcessor(string $processor): PaymentProcessor;
}
