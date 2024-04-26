<?php

namespace App\Coupon\Validator;

use App\Coupon\Entity\Coupon;
use App\Coupon\Repository\CouponRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class CouponCodeExistsValidator extends ConstraintValidator
{
    public function __construct(
        private CouponRepository $couponRepository,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof CouponCodeExists) {
            throw new UnexpectedTypeException($constraint, CouponCodeExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $product = $this->couponRepository->findOneByCode($value);

        if ($product instanceof Coupon) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation();
    }
}
