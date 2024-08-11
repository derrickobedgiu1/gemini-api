<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks;

use Derrickob\GeminiApi\Data\Chunk;
use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\Corpora\Documents\Chunks\BatchCreateChunksResponse;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use InvalidArgumentException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class BatchCreateChunksRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ParameterTypeValidator;
    use ApiEndpointValidator;

    protected Method $method = Method::POST;
    public AuthMethod $auth = AuthMethod::ADC;

    /** @var string The name of the Document where this batch of Chunks will be created. */
    private string $parent;

    private array $requests;

    /**
     * @param array{
     *     parent: string,
     *     requests: array{array{chunk: Chunk}},
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);

        $this->parent = $parameters['parent'];
        $this->requests = $parameters['requests'];
        $this->validateResource(ResourceType::Document, $this->parent);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->parent/chunks:batchCreate";
    }

    public function defaultBody(): array
    {
        return [
            'requests' => array_map(fn ($request): array => [
                'parent' => $this->parent,
                'chunk' => $request['chunk'] instanceof Chunk
                    ? $request['chunk']->toArray()
                    : throw new InvalidArgumentException('chunk must be set and should be an instance of Chunk'),
            ], $this->requests),
        ];
    }

    public function createDtoFromResponse(Response $response): BatchCreateChunksResponse
    {
        $data = $response->json();

        return BatchCreateChunksResponse::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'parent' => ['string'],
            'requests' => ['array'],
        ];
    }
}
