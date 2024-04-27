<?php

namespace App\Coupon\Lib\CouponTypeFactory\Implementation;

use App\Coupon\Entity\Coupon;
use App\Coupon\Exception\UnknownCouponTypeException;
use App\Coupon\Lib\CouponType\CouponType;
use App\Coupon\Lib\CouponTypeFactory\CouponTypeFactory;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class SymfonyCouponTypeFactory implements CouponTypeFactory
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
