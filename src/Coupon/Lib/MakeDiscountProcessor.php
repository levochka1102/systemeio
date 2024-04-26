<?php

namespace App\Coupon\Lib;

use App\Coupon\Entity\Coupon;
use Brick\Money\Money;

final class MakeDiscountProcessor
{
    private Money $money;

    public function __construct(
        private CouponTypeFactory $couponTypeFactory,
    ) {
    }

    public function for(Money $money): static
    {
        $this->money = $money;

        return $this;
    }

    public function applyCoupon(Coupon $coupon): static
    {
        $discount = $this->couponTypeFactory->makeCouponType($coupon)->getDiscount($this->money);
        $this->money = $this->money->minus($discount);

        return $this;
    }

    public function result(): Money
    {
        return $this->money;
    }
}
