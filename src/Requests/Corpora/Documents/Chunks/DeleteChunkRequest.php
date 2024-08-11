<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks;

use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Saloon\Enums\Method;

final class DeleteChunkRequest extends Request
{
    use ApiEndpointValidator;

    protected Method $method = Method::DELETE;
    public AuthMethod $auth = AuthMethod::ADC;

    /**
     * @param string $name The resource name of the `Chunk` to delete
     */
    public function __construct(private readonly string $name)
    {
        $this->validateResource(ResourceType::DocumentChunk, $this->name);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->name";
    }
}
