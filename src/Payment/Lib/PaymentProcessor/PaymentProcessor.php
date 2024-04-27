<?php

namespace App\Payment\Lib\PaymentProcessor;

use App\Payment\Exception\PurchaseFailedException;
use Brick\Money\Money;

interface PaymentProcessor
{
    /**
     * @param Money $amount
     * @return true
     * @throws PurchaseFailedException
     */
    public function purchase(Money $amount): true;
}
