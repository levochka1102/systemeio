<?php

namespace App\Coupon\Lib\CouponTypeFactory;

use App\Coupon\Entity\Coupon;
use App\Coupon\Exception\UnknownCouponTypeException;
use App\Coupon\Lib\CouponType\CouponType;

interface CouponTypeFactory
{
    /**
     * @param Coupon $coupon
     * @return CouponType
     * @throws UnknownCouponTypeException
     */
    public function makeCouponType(Coupon $coupon): CouponType;
}
