<?php

namespace App\Service;

use App\Entity\Currency;
use App\Repository\CurrencyRepository;

readonly class SaveOrUpdateCurrenciesService
{

    public function __construct(
      private CurrencyRepository $repository
    ) {
    }

    public function saveOrUpdateCurrencies($currencies): void
    {
        foreach ($currencies as $externalCurrency) {
            $currency = $this->repository->findOneBy(
              ['currencyCode' => $externalCurrency->code]
            );

            if (!$currency) {
                $currency = new Currency(
                  $externalCurrency->currency,
                  $externalCurrency->code,
                  $externalCurrency->mid
                );
            } else {
                $currency->update($externalCurrency->mid);
            }
            $this->repository->save($currency, true);
        }
    }
}
