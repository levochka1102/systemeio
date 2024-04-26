<?php

namespace App\Vat;

use App\Vat\Exception\UnsopportedCountryCodeException;
use App\TaxNumber\Entity\TaxNumber;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class Vat
{
    /**
     * Map country code to VAT rate.
     *
     * @var array<string, int>
     */
    private array $map;

    public function __construct(
        private ContainerBagInterface $params,
    ) {
        $this->map = $params->get('vat');
    }

    public function calculate(TaxNumber $taxNumber, Money $amount): Money
    {
        $rate = $this->getVatRate($taxNumber);

        return $amount->plus($amount->multipliedBy($rate / 100, RoundingMode::HALF_UP));
    }

    /**
     * @param TaxNumber $taxNumber
     * @return integer
     * @throws UnsopportedCountryCodeException
     */
    private function getVatRate(TaxNumber $taxNumber): int
    {
        if (!isset($this->map[$taxNumber->getCountryCode()])) {
            throw new UnsopportedCountryCodeException($taxNumber->getCountryCode());
        }

        return $this->map[$taxNumber->getCountryCode()];
    }
}
