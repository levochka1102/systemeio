<?php

namespace App\Product\Validator;

use App\Product\Entity\Product;
use App\Product\Repository\ProductRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ProductIdExistsValidator extends ConstraintValidator
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductIdExists) {
            throw new UnexpectedTypeException($constraint, ProductIdExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_int($value)) {
            throw new UnexpectedValueException($value, 'int');
        }

        $product = $this->productRepository->find($value);

        if ($product instanceof Product) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation();
    }
}
