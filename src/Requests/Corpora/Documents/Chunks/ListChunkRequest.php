<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks;

use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\Corpora\Documents\Chunks\ListChunkResponse;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Enums\Method;
use Saloon\Http\Response;

final class ListChunkRequest extends Request
{
    use ParameterTypeValidator;
    use ApiEndpointValidator;

    protected Method $method = Method::GET;
    public AuthMethod $auth = AuthMethod::ADC;

    /** @var int|null The maximum number of Chunks to return (per page). */
    private ?int $pageSize;

    /** @var string|null A page token, received from a previous chunks.list call. */
    private ?string $pageToken;

    /** @var string The name of the Document containing Chunks. */
    private string $parent;

    /**
     * @param array{
     *     parent: string,
     *     pageSize?: int,
     *     pageToken?: string,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);

        $this->parent = $parameters['parent'];
        $this->pageSize = $parameters['pageSize'] ?? null;
        $this->pageToken = $parameters['pageToken'] ?? null;
        $this->validateResource(ResourceType::Document, $this->parent);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->parent/chunks";
    }

    public function defaultQuery(): array
    {
        return array_filter(['pageSize' => $this->pageSize, 'pageToken' => $this->pageToken]);
    }

    public function createDtoFromResponse(Response $response): ListChunkResponse
    {
        $data = $response->json();

        return ListChunkResponse::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'parent' => ['string'],
            'pageSize' => ['int', 'null'],
            'pageToken' => ['string', 'null'],
        ];
    }
}
