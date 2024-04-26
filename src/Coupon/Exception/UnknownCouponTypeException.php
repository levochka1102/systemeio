<?php

namespace App\Coupon\Exception;

use App\Coupon\Entity\Coupon;
use Exception;

class UnknownCouponTypeException extends Exception
{

    public function __construct(Coupon $coupon, Exception $previous = null)
    {
        $message = sprintf('Unknown coupon type of value "%s"', $coupon->getValue());

        parent::__construct($message, 0, $previous);
    }
}
