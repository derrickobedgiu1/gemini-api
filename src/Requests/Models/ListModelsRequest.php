<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Models;

use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\Models\ListModelsResponse;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * list models
 */
final class ListModelsRequest extends Request
{
    use ParameterTypeValidator;

    protected Method $method = Method::GET;
    private ?int $pageSize;
    private ?string $pageToken;

    /**
     * @param array{
     *     pageSize?: int,
     *     pageToken?: string,
     * } $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->validateParameters($parameters);
        $this->pageSize = $parameters['pageSize'] ?? null;
        $this->pageToken = $parameters['pageToken'] ?? null;
    }

    protected function defaultQuery(): array
    {
        return array_filter(['pageSize' => $this->pageSize, 'pageToken' => $this->pageToken]);
    }

    public function resolveEndpoint(): string
    {
        return "/models";
    }

    public function createDtoFromResponse(Response $response): ListModelsResponse
    {
        $data = $response->json();

        return ListModelsResponse::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'pageSize' => ['int', 'null'],
            'pageToken' => ['string', 'null'],
        ];
    }
}
