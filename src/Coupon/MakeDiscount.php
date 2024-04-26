<?php

namespace App\Coupon;

use App\Coupon\Lib\MakeDiscountProcessor;
use Brick\Money\Money;

final class MakeDiscount
{
    public function __construct(
        private MakeDiscountProcessor $processor,
    ) {
    }

    public function for(Money $money): MakeDiscountProcessor
    {
        $this->processor->for($money);

        return $this->processor;
    }
}
