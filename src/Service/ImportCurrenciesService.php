<?php

namespace App\Service;

use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class ImportCurrenciesService
{

    public function __construct(
      private HttpClientInterface $client,
      private SaveOrUpdateCurrenciesService $service
    ) {
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
            $this->service->saveOrUpdateCurrencies($currencies);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
