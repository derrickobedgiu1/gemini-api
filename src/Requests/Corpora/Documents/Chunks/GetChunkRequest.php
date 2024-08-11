<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks;

use Derrickob\GeminiApi\Data\Chunk;
use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Saloon\Enums\Method;
use Saloon\Http\Response;

final class GetChunkRequest extends Request
{
    use ApiEndpointValidator;

    protected Method $method = Method::GET;
    public AuthMethod $auth = AuthMethod::ADC;

    /**
     * @param string $name The name of the Chunk to retrieve.
     */
    public function __construct(private readonly string $name)
    {
        $this->validateResource(ResourceType::DocumentChunk, $this->name);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->name";
    }

    public function createDtoFromResponse(Response $response): Chunk
    {
        $data = $response->json();

        return Chunk::fromArray($data);
    }
}
