<?php

namespace App\Coupon\Lib;

use Brick\Money\Money;
use InvalidArgumentException;

class FixedCouponType implements CouponType
{
    private Money $fixed;

    public function __construct(string $value)
    {
        $res = preg_match('/^\d+$/', $value, $matches);

        if ($res === 0) {
            throw new InvalidArgumentException(sprintf('Invalid coupon value "%s"', $value));
        }

        $this->fixed = Money::ofMinor((int) $matches[0], 'EUR');
    }

    /**
     * @inheritDoc
     */
    public function getDiscount(Money $money): Money
    {
        if ($this->fixed->isGreaterThan($money)) {
            return $money;
        }

        return $this->fixed;
    }
}
