<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Brick\Money\Money;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 3)]
    private ?string $currencyCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): Money
    {
        return Money::ofMinor($this->price, $this->currencyCode);
    }

    public function setPrice(Money $price): static
    {
        $this->price = $price->getMinorAmount()->toInt();
        $this->currencyCode = $price->getCurrency()->getCurrencyCode();

        return $this;
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }
}
