<?php

namespace App\Coupon\Lib;

use App\Coupon\Entity\Coupon;
use App\Coupon\Exception\UnknownCouponTypeException;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class CouponTypeFactory
{
    public function __construct(
        private ContainerBagInterface $params,
    ) {
    }

    /**
     * @param Coupon $coupon
     * @return CouponType
     * @throws UnknownCouponTypeException
     */
    public function makeCouponType(Coupon $coupon): CouponType
    {
        foreach ($this->params->get('couponTypes') as $regex => $class) {
            if (preg_match($regex, $coupon->getValue(), $matches)) {
                return new $class($coupon->getValue());
            }
        }

        throw new UnknownCouponTypeException($coupon);
    }
}
