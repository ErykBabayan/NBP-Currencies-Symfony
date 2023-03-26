<?php

namespace App\DTO;

use App\DTO\Interface\Resource;
use App\Entity\Currency;
use Symfony\Component\Uid\Uuid;

class CurrencyResource implements Resource
{

    public function __construct(
      public uuid $uuid,
      public string $name,
      public string $currencyCode,
      public float $exchangeRate
    ) {
    }


    /**
     * @param  array  $objects
     *
     * @return array
     */
    public static function fromObjects(array $objects): array
    {
        $resources = [];
        foreach ($objects as $object) {
            $resources[] = self::fromObject($object);
        }
        return $resources;
    }

    /**
     * @param  Currency  $object
     *
     * @return CurrencyResource
     */
    public static function fromObject($object): self
    {
        return new self(
          uuid: $object->getId(),
          name: $object->getName(),
          currencyCode: $object->getCurrencyCode(),
          exchangeRate: $object->getExchangeRate()
        );
    }

}
