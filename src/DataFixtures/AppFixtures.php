<?php

namespace App\DataFixtures;

use App\Entity\Coupon;
use App\Entity\Product;
use Brick\Money\Money;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Generator;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->populateProducts() as $product) {
            $manager->persist($product);
        }

        foreach ($this->populateCoupons() as $coupon) {
            $manager->persist($coupon);
        }

        $manager->flush();
    }

    private function populateProducts(): Generator
    {
        $product = new Product();
        $product->setName('Iphone');
        $product->setPrice(Money::of(105, 'EUR'));
        yield $product;

        $product = new Product();
        $product->setName('JBL 215TWS');
        $product->setPrice(Money::of(20, 'EUR'));
        yield $product;

        $product = new Product();
        $product->setName('Phone Case');
        $product->setPrice(Money::of(10, 'EUR'));
        yield $product;
    }

    private function populateCoupons(): Generator
    {
        $coupon = new Coupon();
        $coupon->setCode('COUPON1');
        $coupon->setValue('100%');
        yield $coupon;

        $coupon = new Coupon();
        $coupon->setCode('COUPON2');
        $coupon->setValue('50EUR');
        yield $coupon;
    }
}
