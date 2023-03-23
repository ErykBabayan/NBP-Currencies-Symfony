<?php

declare(strict_types=1);

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;

class AbstractController extends AbstractFOSRestController
{
    protected function response(
            $data,
            int $statusCode = Response::HTTP_OK,
    ): Response {
        $view = $this->view($data, $statusCode);


        return $this->handleView($view);
    }

    protected function emptyResponse(): Response
    {
        return $this->response( null);
    }

}
