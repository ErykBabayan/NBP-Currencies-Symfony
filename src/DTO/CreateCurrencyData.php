<?php

namespace App\DTO;


use App\DTO\Interface\DTO;

class CreateCurrencyData implements DTO
{

    public function __construct(
      public string $name,
      public string $currencyCode,
      public float $exchangeRate
    ) {
    }

}
