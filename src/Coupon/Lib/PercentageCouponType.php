<?php

namespace App\Coupon\Lib;

use Brick\Math\RoundingMode;
use Brick\Money\Money;
use InvalidArgumentException;

class PercentageCouponType implements CouponType
{
    private int $percentage;

    public function __construct(string $value)
    {
        $res = preg_match('/^\d+/', $value, $matches);

        if ($res === 0) {
            throw new InvalidArgumentException(sprintf('Invalid coupon value "%s"', $value));
        }

        $percentage = (int) $matches[0];
        $percentage = min($percentage, 100);

        $this->percentage = $percentage;
    }

    /**
     * @inheritDoc
     */
    public function getDiscount(Money $money): Money
    {
        $discount = $money->multipliedBy($this->percentage / 100, RoundingMode::HALF_UP);

        return $discount;
    }
}
