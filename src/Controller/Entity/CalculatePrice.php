<?php

namespace App\Controller\Entity;

use App\TaxNumber\Entity\TaxNumber;
use App\Product\Validator as ProductAssert;
use App\Coupon\Validator as CouponAssert;
use Symfony\Component\Validator\Constraints as Assert;


readonly class CalculatePrice
{
    #[Assert\NotBlank]
    #[ProductAssert\ProductIdExists]
    public int $product;

    #[CouponAssert\CouponCodeExists]
    public ?string $couponCode;

    #[Assert\Valid]
    public TaxNumber $taxNumber;

    public function __construct(
        int $product,
        string $taxNumber,
        ?string $couponCode = null,
    ) {
        $this->product = $product;
        $this->taxNumber = new TaxNumber($taxNumber);
        $this->couponCode = $couponCode;
    }
}
