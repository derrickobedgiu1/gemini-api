<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Exceptions;

use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Response;
use Throwable;

final class ApiRequestException extends RequestException
{
    public function __construct(Response $response, ?Throwable $throwable = null)
    {
        parent::__construct(
            $response,
            $this->getExceptionMessage($response),
            $response->status(),
            $throwable
        );
    }

    private function getExceptionMessage(Response $response): string
    {
        $statusCode = $response->status();
        $body = $response->body();

        return "Error ($statusCode): \n$body";
    }
}
