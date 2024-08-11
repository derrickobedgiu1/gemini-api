<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks;

use Derrickob\GeminiApi\Data\Chunk;
use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class CreateChunkRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ParameterTypeValidator;
    use ApiEndpointValidator;

    protected Method $method = Method::POST;
    public AuthMethod $auth = AuthMethod::ADC;

    /** @var Chunk The field data for creating the chunk. */
    private Chunk $chunk;

    /** @var string The name of the Document where this Chunk will be created. */
    private string $parent;

    /**
     * @param array{
     *     parent: string,
     *     chunk: Chunk,
     * } $parameters
     */
    public function __construct(protected array $parameters)
    {
        $this->validateParameters($this->parameters);

        $this->parent = $this->parameters['parent'];
        $this->chunk = $this->parameters['chunk'];
        $this->validateResource(ResourceType::Document, $this->parent);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->parent/chunks";
    }

    public function defaultBody(): array
    {
        return $this->chunk->toArray();
    }

    public function createDtoFromResponse(Response $response): Chunk
    {
        $data = $response->json();

        return Chunk::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'parent' => ['string'],
            'chunk' => [Chunk::class],
        ];
    }
}
