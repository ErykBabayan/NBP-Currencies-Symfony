<?php

namespace App\Service;

use App\DTO\CreateCurrencyData;
use App\DTO\UpdateCurrencyData;
use App\Entity\Currency;
use Exception;
use App\Repository\CurrencyRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class CurrencyService
{

    public function __construct(
      private CurrencyRepository $repository,
      private HttpClientInterface $client,
    ) {
    }

    public function create(CreateCurrencyData $data): Currency
    {
        $currency = new Currency($data);
        $this->repository->save($currency, true);

        return $currency;
    }

    public function update(Currency $currency, UpdateCurrencyData $data): Currency
    {
        $currency->update($data);
        return $currency;
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Exception
     */
    public function importCurrencies(): void
    {
        try {
            $response   = $this->client->request(
              'GET',
              'https://api.nbp.pl/api/exchangerates/tables/B?format=json'
            );
            $currencies = json_decode($response->getContent())[0]->rates;
            $this->saveOrUpdateImportedCurrencies($currencies);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function saveOrUpdateImportedCurrencies($currencies): void
    {
        foreach ($currencies as $externalCurrency) {
            $currency = $this->repository->findOneBy(
              ['currencyCode' => $externalCurrency->code]
            );

            if (!$currency) {
                $currencyDto = new CreateCurrencyData(
                  $externalCurrency->currency,
                  $externalCurrency->code,
                  $externalCurrency->mid
                );

                $currency = new Currency($currencyDto);
            } else {
                $currencyDto = new UpdateCurrencyData(
                  null,
                  null,
                  $externalCurrency->mid
                );

                $currency = $this->update($currency, $currencyDto);
            }
            $this->repository->save($currency, true);
        }
    }

}
