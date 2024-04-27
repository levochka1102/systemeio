<?php

namespace App\Controller\Entity;

use App\TaxNumber\Entity\TaxNumber;
use App\Product\Validator as ProductAssert;
use App\Coupon\Validator as CouponAssert;
use Symfony\Component\Validator\Constraints as Assert;
use App\Payment\Validator as PaymentAssert;


readonly class Purchase
{
    #[Assert\NotBlank]
    #[ProductAssert\ProductIdExists]
    public int $product;

    #[CouponAssert\CouponCodeExists]
    public ?string $couponCode;

    #[Assert\Valid]
    public TaxNumber $taxNumber;

    #[Assert\NotBlank]
    #[PaymentAssert\PaymentProcessorExists]
    public string $paymentProcessor;

    public function __construct(
        int $product,
        string $taxNumber,
        string $paymentProcessor,
        ?string $couponCode = null,
    ) {
        $this->product = $product;
        $this->taxNumber = new TaxNumber($taxNumber);
        $this->paymentProcessor = $paymentProcessor;
        $this->couponCode = $couponCode;
    }
}
