<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Resources\TunedModels;

use Derrickob\GeminiApi\Contracts\Resources\TunedModels\TunedModelOperationsContract;
use Derrickob\GeminiApi\Data\Operation;
use Derrickob\GeminiApi\Requests\TunedModels\Operations\CancelOperationRequest;
use Derrickob\GeminiApi\Requests\TunedModels\Operations\GetOperationRequest;
use Derrickob\GeminiApi\Requests\TunedModels\Operations\ListOperationRequest;
use Derrickob\GeminiApi\Responses\TunedModels\Operations\ListOperationsResponse;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\BaseResource;

final class TunedModelOperations extends BaseResource implements TunedModelOperationsContract
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
    public function cancel(string $name): bool
    {
        /** @var bool */
        return $this->connector->send(new CancelOperationRequest($name))->successful();
    }

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
    public function get(string $name): Operation
    {
        /** @var Operation */
        return $this->connector->send(new GetOperationRequest($name))->dtoOrFail();
    }

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
    public function list(array $parameters): ListOperationsResponse
    {
        /** @var ListOperationsResponse */
        return $this->connector->send(new ListOperationRequest($parameters))->dtoOrFail();
    }
}
