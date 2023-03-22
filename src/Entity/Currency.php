<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $currencyCode;

    #[ORM\Column]
    private float $exchangeRate;

    public function __construct(
      string $name,
      string $currencyCode,
      float $exchangeRate
    ) {
        $this->name         = $name;
        $this->currencyCode = $currencyCode;
        $this->exchangeRate = $exchangeRate;
    }

    public function update($exchangeRate)
    {
        $this->exchangeRate = $exchangeRate;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function getExchangeRate(): float
    {
        return $this->exchangeRate;
    }

}
