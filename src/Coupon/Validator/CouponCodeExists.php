<?php

namespace App\Coupon\Validator;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class CouponCodeExists extends Constraint
{
    public string $message = 'The coupon with code "{{ string }}" does not exists.';

    #[HasNamedArguments]
    public function __construct(
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct([], $groups, $payload);
    }
}
