<?php

namespace App\Product\Entity;

use App\Product\Repository\ProductRepository;
use Brick\Money\Money;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Product with this name already exists')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $price = null;

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
        return Money::ofMinor($this->price, 'EUR');
    }

    public function setPrice(Money $price): static
    {
        $this->price = $price->getMinorAmount()->toInt();

        return $this;
    }
}
