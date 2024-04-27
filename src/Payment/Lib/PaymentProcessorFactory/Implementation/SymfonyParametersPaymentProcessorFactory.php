<?php

namespace App\Payment\Lib\PaymentProcessorFactory\Implementation;

use App\Payment\Exception\UnknownProcessorException;
use App\Payment\Lib\PaymentProcessor\PaymentProcessor;
use App\Payment\Lib\PaymentProcessorFactory\PaymentProcessorFactory;
use Symfony\Component\DependencyInjection\Container;

class SymfonyParametersPaymentProcessorFactory implements PaymentProcessorFactory
{
    /**
     * Map processor name to related processor class name.
     * 
     * @var array<string, string>
     */
    private array $map;

    public function __construct(
        private Container $container,
    ) {
        $this->map = $container->getParameter('paymentProcessors');
    }

    /**
     * @inheritDoc
     */
    public function createProcessor(string $processor): PaymentProcessor
    {
        if (!isset($this->map[$processor])) {
            throw new UnknownProcessorException($processor);
        }

        return $this->container->get($this->map[$processor]);
    }
}
