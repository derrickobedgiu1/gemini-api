<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks;

use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

final class BatchDeleteChunksRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ApiEndpointValidator;
    use ParameterTypeValidator;

    protected Method $method = Method::POST;
    public AuthMethod $auth = AuthMethod::ADC;

    /** @var string The name of the Document containing the Chunks to delete. */
    private string $parent;

    /** @var string[] The request messages specifying the Chunks to delete. */
    private array $requests;

    /**
     * @param array{
     *     parent: string,
     *     requests: string[],
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
        return "/$this->parent/chunks:batchDelete";
    }

    public function defaultBody(): array
    {
        return [
            'requests' => array_map(fn ($request): array => [
                'name' => $request,
            ], $this->requests),
        ];
    }

    protected function expectParameters(): array
    {
        return [
            'parent' => ['string'],
            'requests' => ['array'],
        ];
    }
}
