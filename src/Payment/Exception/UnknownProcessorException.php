<?php

namespace App\Payment\Exception;

use Exception;

class UnknownProcessorException extends Exception
{
    public function __construct(string $processor, Exception $previous = null)
    {
        $message = sprintf('Unknown processor "%s"', $processor);

        parent::__construct($message, 0, $previous);
    }
}
