<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalculatePriceEndpointTest extends WebTestCase
{
    private KernelBrowser $client;
    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    public function testNoCoupon(): void
    {
        $amount = $this->calculatePrice(1, "DE123456789");
        $this->assertResponseIsSuccessful();
        $this->assertEquals(124.95, $amount);

        $amount = $this->calculatePrice(1, "FRHN123423423");
        $this->assertResponseIsSuccessful();
        $this->assertEquals(126, $amount);

        $amount = $this->calculatePrice(2, "GR123423423");
        $this->assertResponseIsSuccessful();
        $this->assertEquals(24.8, $amount);
    }

    public function testPercentageCoupon(): void
    {
        $amount = $this->calculatePrice(2, "GR123423423", "COUPON3");
        $this->assertResponseIsSuccessful();
        $this->assertEquals(19.1, $amount);

        $amount = $this->calculatePrice(1, "DE123456789", "COUPON3");
        $this->assertResponseIsSuccessful();
        $this->assertEquals(96.21, $amount);

        $amount = $this->calculatePrice(1, "DE123456789", "COUPON1");
        $this->assertResponseIsSuccessful();
        $this->assertEquals(0, $amount);
    }

    public function testFixedCoupon(): void
    {
        $amount = $this->calculatePrice(1, "DE123456789", "COUPON2");
        $this->assertResponseIsSuccessful();
        $this->assertEquals(65.45, $amount);

        $amount = $this->calculatePrice(3, "DE123456789", "COUPON2");
        $this->assertResponseIsSuccessful();
        $this->assertEquals(0, $amount);

        $amount = $this->calculatePrice(3, "IT12342342342", "COUPON4");
        $this->assertResponseIsSuccessful();
        $this->assertEquals(5.8, $amount);
    }

    public function testProductNotFound(): void
    {
        $res = $this->calculatePrice(666, "IT12342342342", "COUPON4");
        $this->assertResponseStatusCodeSame(400);
        $this->assertStringContainsString('The product id "666" does not exists', $res['detail']);
    }

    public function testTaxNumberIsNotSupportedCountry(): void
    {
        $res = $this->calculatePrice(1, "PL1234234232", "COUPON4");
        $this->assertResponseStatusCodeSame(400);
    }
    public function testTaxNumberIsInvalid(): void
    {
        $res = $this->calculatePrice(1, "FRN123423423", "COUPON4");
        $this->assertResponseStatusCodeSame(400);
    }

    public function testCouponNotFound(): void
    {
        $res = $this->calculatePrice(1, "FRNJ123423423", "COUPON224");
        $this->assertResponseStatusCodeSame(400);
        $this->assertStringContainsString('The coupon with code "COUPON224" does not exists', $res['detail']);
    }

    private function calculatePrice(int $product, string $taxNumber, ?string $couponCode = null): float|array
    {
        $params = [
            "product" => $product,
            "taxNumber" =>  $taxNumber,
        ];

        if ($couponCode) {
            $params['couponCode'] = $couponCode;
        }

        $this->client->jsonRequest(
            method: 'POST',
            uri: '/calculate-price',
            parameters: $params,
        );
        $response = $this->client->getResponse();
        $this->assertJson($response->getContent());

        if ($response->isSuccessful()) {
            return json_decode($response->getContent(), true)['price']['amount'];
        }

        return json_decode($response->getContent(), true);
    }
}
