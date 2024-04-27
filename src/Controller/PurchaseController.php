<?php

namespace App\Controller;

use App\Coupon\Repository\CouponRepository;
use App\Product\Repository\ProductRepository;
use App\Controller\Entity\Purchase;
use App\Payment\Exception\PurchaseFailedException;
use App\Payment\GetPrice;
use App\Payment\Payment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpFoundation\Response;

class PurchaseController extends AbstractController
{
    #[Route('/purchase', name: 'purchase', methods: ['POST'], format: 'json')]
    public function purchase(
        #[MapRequestPayload(acceptFormat: 'json', validationFailedStatusCode: Response::HTTP_BAD_REQUEST)]
        Purchase $payload,
        ProductRepository $productRepository,
        CouponRepository $couponRepository,
        GetPrice $getPrice,
        Payment $payment,
    ): Response {
        $product = $productRepository->find($payload->product);
        $coupons = [];

        if ($payload->couponCode) {
            $coupons[] = $couponRepository->findOneByCode($payload->couponCode);
        }

        $amount = $getPrice->for($payload->taxNumber, [$product], $coupons);

        try {
            $payment->purchase($amount, $payload->paymentProcessor);
        } catch (PurchaseFailedException $e) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, $e->getMessage(), $e);
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
