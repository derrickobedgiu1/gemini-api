<?php

namespace Derrickob\GeminiApi\Requests\TunedModels\Operations;

use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\TunedModels\Operations\ListOperationsResponse;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Enums\Method;
use Saloon\Http\Response;

final class ListOperationRequest extends Request
{
    use ParameterTypeValidator;
    use ApiEndpointValidator;

    protected Method $method = Method::GET;
    public AuthMethod $auth = AuthMethod::ADC;
    private string $name;
    private ?string $filter;
    private ?int $pageSize;
    private ?string $pageToken;

    /**
     * @param array{
     *     name: string,
     *     filter?: string,
     *     pageSize?: int,
     *     pageToken?: string,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);

        $this->name = $parameters['name'];
        $this->filter = $parameters['filter'] ?? null;
        $this->pageSize = $parameters['pageSize'] ?? null;
        $this->pageToken = $parameters['pageToken'] ?? null;
        $this->validateResource(ResourceType::Operation, $this->name);
    }

    public function resolveEndpoint(): string
    {
        return "https://generativelanguage.googleapis.com/v1/$this->name/operations";
    }

    public function defaultQuery(): array
    {
        return array_filter(['pageSize' => $this->pageSize, 'pageToken' => $this->pageToken, 'filter' => $this->filter]);
    }

    public function createDtoFromResponse(Response $response): ListOperationsResponse
    {
        $data = $response->json();

        return ListOperationsResponse::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'name' => ['string'],
            'filter' => ['string', 'null'],
            'pageSize' => ['int', 'null'],
            'pageToken' => ['string', 'null'],
        ];
    }
}
