<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Corpora\Documents;

use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Enums\Method;

final class DeleteDocumentRequest extends Request
{
    use ParameterTypeValidator;
    use ApiEndpointValidator;

    protected Method $method = Method::DELETE;
    public AuthMethod $auth = AuthMethod::ADC;

    /** @var string The resource name of the Document to delete. */
    private string $name;

    /** @var bool|null If set to true, any Chunks and objects related to this Document will also be deleted. */
    private ?bool $force;

    /**
     * @param array{
     *     name: string,
     *     force?: bool,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);

        $this->name = $parameters['name'];
        $this->force = $parameters['force'] ?? null;
        $this->validateResource(ResourceType::Document, $this->name);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->name";
    }

    protected function defaultQuery(): array
    {
        return array_filter(['force' => $this->force]);
    }

    protected function expectParameters(): array
    {
        return [
            'name' => ['string'],
            'force' => ['bool', 'null'],
        ];
    }
}
