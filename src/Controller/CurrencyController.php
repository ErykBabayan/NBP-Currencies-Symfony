<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreateCurrencyData;
use App\DTO\CurrencyResource;
use App\DTO\UpdateCurrencyData;
use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use App\Service\CurrencyService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;


class CurrencyController extends AbstractController
{

    public function __construct(
      private readonly CurrencyRepository $repository,
      private readonly CurrencyService $service
    ) {
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    #[Rest\Get(
      path: "/currencies",
    )]
    public function index(): Response
    {
        $currencies = $this->repository->findAll();
        $this->service->importCurrencies();

        return $this->response(
          data: CurrencyResource::fromObjects($currencies)
        );
    }

    #[Rest\Get(
      path: "/currency/{id}",
    )]
    public function show(
      Currency $currency
    ): Response {
        return $this->response(data: CurrencyResource::fromObject($currency));
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    #[Rest\Post(
      path: "/currency",
    )]
    public function create(
      CreateCurrencyData $data
    ): Response {
        $this->service->importCurrencies();
        $currency = $this->service->create($data);
        return $this->response(
          data: CurrencyResource::fromObject($currency),
          statusCode: Response::HTTP_CREATED
        );
    }

    #[Rest\Patch(
      path: "/currency/{id}",
    )]
    public function patch(
      UpdateCurrencyData $data,
      Currency $currency
    ): Response {
        $this->service->update($currency, $data);
        return $this->response(data: CurrencyResource::fromObject($currency));
    }

    #[Rest\Delete(
      path: "/currency/{id}",
    )]
    public function delete(
      Currency $currency
    ): Response {
        $this->repository->remove($currency, true);
        return $this->emptyResponse();
    }

}
