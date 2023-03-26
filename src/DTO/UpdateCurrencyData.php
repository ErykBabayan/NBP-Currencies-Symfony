<?php

namespace App\DTO;

use App\DTO\Interface\DTO;

class UpdateCurrencyData implements DTO
{

    public function __construct(
      public ?string $name,
      public ?string $currencyCode,
      public ?float $exchangeRate
    ) {
    }

}