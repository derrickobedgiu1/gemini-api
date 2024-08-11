<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Contracts\Resources\TunedModels;

use Derrickob\GeminiApi\Data\Operation;
use Derrickob\GeminiApi\Responses\TunedModels\Operations\ListOperationsResponse;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

interface TunedModelOperationsContract
{
    /**
     * Starts asynchronous cancellation on a long-running operation. The server makes a best effort to cancel the operation, but success is not guaranteed.
     *
     * @param string $name The name of the operation resource to be cancelled.
     *
     * @return bool If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function cancel(string $name): bool;

    /**
     * Gets the latest state of a long-running operation. Clients can use this method to poll the operation result at intervals as recommended by the API service.
     *
     * @param string $name The name of the operation resource.
     *
     * @return Operation If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function get(string $name): Operation;

    /**
     * Lists operations that match the specified filter in the request. If the server doesn't support this method, it returns `UNIMPLEMENTED`.
     *
     * @param array{
     *     name: string,
     *     filter?: string,
     *     pageSize?: int,
     *     pageToken?: string,
     * } $parameters
     *
     * @return ListOperationsResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function list(array $parameters): ListOperationsResponse;
}
