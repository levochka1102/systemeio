<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PurchaseEndpointTest extends WebTestCase
{
    const PAYPAL = 'paypal';
    const STRIPE = 'stripe';

    private KernelBrowser $client;
    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    public function testPaypalSuccess(): void
    {
        $this->purchase(1, "DE123456789", self::PAYPAL);
        $this->assertResponseIsSuccessful();

        $this->purchase(3, "FRHN123423423", self::PAYPAL);
        $this->assertResponseIsSuccessful();

        $this->purchase(2, "GR123423423", self::PAYPAL);
        $this->assertResponseIsSuccessful();

        $this->purchase(4, "GR123423423", self::PAYPAL, "COUPON3");
        $this->assertResponseIsSuccessful();
    }

    public function testPaypalFail(): void
    {
        $this->purchase(4, "GR123423423", self::PAYPAL);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testStripeSuccess(): void
    {
        $this->purchase(1, "DE123456789", self::STRIPE);
        $this->assertResponseIsSuccessful();

        $this->purchase(4, "GR123423423", self::STRIPE, "COUPON3");
        $this->assertResponseIsSuccessful();
    }

    public function testStripeFail(): void
    {
        $this->purchase(3, "FRHN123423423", self::STRIPE);
        $this->assertResponseStatusCodeSame(400);

        $this->purchase(2, "GR123423423", self::STRIPE);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testProductNotFound(): void
    {
        $res = $this->purchase(66, "DE123456789", self::STRIPE);
        $this->assertResponseStatusCodeSame(400);
        $this->assertStringContainsString('The product id "66" does not exists', $res['detail']);
    }

    public function testTaxNumberIsInvalid(): void
    {
        $res = $this->purchase(1, "FRN123423423", self::STRIPE);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testCouponNotFound(): void
    {
        $res = $this->purchase(66, "DE123456789", self::STRIPE, "COUPON224");
        $this->assertResponseStatusCodeSame(400);
        $this->assertStringContainsString('The coupon with code "COUPON224" does not exists', $res['detail']);
    }

    private function purchase(
        int $product,
        string $taxNumber,
        string $paymentProcessor,
        ?string $couponCode = null
    ): array|null {
        $params = [
            "product" => $product,
            "taxNumber" =>  $taxNumber,
            "paymentProcessor" =>  $paymentProcessor,
        ];

        if ($couponCode) {
            $params['couponCode'] = $couponCode;
        }

        $this->client->jsonRequest(
            method: 'POST',
            uri: '/purchase',
            parameters: $params,
        );
        $response = $this->client->getResponse();

        return json_decode($response->getContent(), true);
    }
}
