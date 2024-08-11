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

final class PatchChunkRequest extends Request implements HasBody
{
    use HasJsonBody;

    use ParameterTypeValidator;
    use ApiEndpointValidator;

    protected Method $method = Method::PATCH;
    public AuthMethod $auth = AuthMethod::ADC;

    /** @var string The Chunk resource name. */
    private string $name;

    /** @var string The list of fields to update. */
    private string $updateMask;

    /** @var Chunk The field data for the chunk to update. */
    private Chunk $chunk;

    /**
     * @param array{
     *     name: string,
     *     updateMask: string,
     *     chunk: Chunk,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);

        $this->name = $parameters['name'];
        $this->updateMask = $parameters['updateMask'];
        $this->chunk = $parameters['chunk'];
        $this->validateResource(ResourceType::DocumentChunk, $this->name);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->name";
    }

    public function defaultBody(): array
    {
        return $this->chunk->toArray();
    }

    public function defaultQuery(): array
    {
        return ['updateMask' => $this->updateMask];
    }

    public function createDtoFromResponse(Response $response): Chunk
    {
        $data = $response->json();

        return Chunk::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'name' => ['string'],
            'updateMask' => ['string'],
            'chunk' => [Chunk::class],
        ];
    }
}
