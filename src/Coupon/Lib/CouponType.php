<?php

namespace App\Coupon\Lib;

use Brick\Money\Money;

interface CouponType
{
    /**
     * Returns the discount for the given price, not a price with applied discount.
     *
     * @param Money $price
     * @return Money
     */
    public function getDiscount(Money $money): Money;
}
