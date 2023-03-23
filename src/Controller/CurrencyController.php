<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class CurrencyController extends AbstractController
{

    public function __construct(
      private readonly CurrencyRepository $repository
    ) {
    }

    #[Rest\Get(
      path: "/currency/{id}",
    )]
    public function index(
      Currency $currency
    ): Response {
        return $this->response($currency);
    }

    #[Rest\Get(
      path: "/currencies",
    )]
    public function show(): Response
    {
        $currencies = $this->repository->findAll();
        return $this->response($currencies);
    }

    #[Rest\Post(
      path: "/currency",
    )]
    public function create(
      Request $request
    ): Response {
        $requestData = json_decode($request->getContent());
        $currency    = new Currency(
          $requestData->name,
          $requestData->currencyCode,
          $requestData->exchangeRate
        );

        $this->repository->save($currency, true);

        return $this->response($currency);
    }

    #[Rest\Patch(
      path: "/currency/{id}",
    )]
    public function patch(
      Request $request,
      Currency $currency
    ): Response {
        $requestData = json_decode($request->getContent());

        $currency->update($requestData->exchangeRate);
        $this->repository->save($currency,true);
        return $this->response($currency);
    }

    #[Rest\Delete(
      path: "/currency/{id}",
    )]
    public function delete(
      Currency $currency
    ): Response {
        $this->repository->remove($currency);
        return $this->emptyResponse();
    }
}
