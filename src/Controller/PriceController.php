<?php

namespace App\Controller;

use App\Coupon\Entity\Coupon;
use App\Coupon\Repository\CouponRepository;
use App\Product\Repository\ProductRepository;
use App\Controller\Entity\CalculatePrice;
use App\Payment\GetPrice;
use App\Product\Entity\Product;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpFoundation\Response;


class PriceController extends AbstractController
{
    #[Route('/calculate-price', name: 'calculate_price', methods: ['POST'], format: 'json')]
    public function calculatePrice(
        #[MapRequestPayload(acceptFormat: 'json', validationFailedStatusCode: Response::HTTP_BAD_REQUEST)]
        CalculatePrice $payload,
        ProductRepository $productRepository,
        CouponRepository $couponRepository,
        GetPrice $getPrice,
    ): JsonResponse {
        $product = $productRepository->find($payload->product);
        $coupons = [];

        if ($payload->couponCode) {
            $coupons[] = $couponRepository->findOneByCode($payload->couponCode);
        }

        $price = $getPrice->for($payload->taxNumber, [$product], $coupons);

        return $this->json(['price' => $price]);
    }
}
