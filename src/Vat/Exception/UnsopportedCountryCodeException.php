<?php

namespace App\Vat\Exception;

use Exception;

class UnsopportedCountryCodeException extends Exception
{
    public function __construct(string $countryCode, Exception $previous = null)
    {
        $message = sprintf('Unsopported country code "%s"', $countryCode);

        parent::__construct($message, 0, $previous);
    }
}
