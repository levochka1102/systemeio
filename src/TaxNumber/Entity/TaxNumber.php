<?php

namespace App\TaxNumber\Entity;

use Symfony\Component\Validator\Constraints as Assert;

use App\TaxNumber\Validator as TaxNumberAssert;

readonly class TaxNumber
{
    #[Assert\NotBlank]
    #[TaxNumberAssert\TaxNumber]
    public string $number;

    public function __construct(
        string $number,
    ) {
        $this->number = strtoupper($number);
    }

    public function getCountryCode(): string
    {
        return substr($this->number, 0, 2);
    }
}
