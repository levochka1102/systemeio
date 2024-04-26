<?php

namespace App\Payment;

use App\Coupon\Entity\Coupon;
use App\Coupon\MakeDiscount;
use App\Product\Entity\Product;
use App\TaxNumber\Entity\TaxNumber;
use App\Vat\Vat;
use Brick\Money\Money;

class GetPrice
{
    public function __construct(
        private MakeDiscount $makeDiscount,
        private Vat $vat,
    ) {
    }

    /**
     * @param TaxNumber $taxNumber
     * @param Product[] $products
     * @param Coupon[] $coupons
     * @return Money
     */
    public function for(TaxNumber $taxNumber, array $products, array $coupons = []): Money
    {
        $target = Money::of(0, 'EUR');

        foreach ($products as $product) {
            $target = $target->plus($product->getPrice());
        }

        if ($coupons) {
            $processor = $this->makeDiscount->for($target);

            foreach ($coupons as $coupon) {
                $processor->applyCoupon($coupon);
            }

            $target = $processor->result();
        }

        $target = $this->vat->calculate($taxNumber, $target);

        return $target;
    }
}
