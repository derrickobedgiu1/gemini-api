<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Corpora;

use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Enums\Method;

final class DeleteCorporaRequest extends Request
{
    use ApiEndpointValidator;
    use ParameterTypeValidator;

    protected Method $method = Method::DELETE;
    public AuthMethod $auth = AuthMethod::ADC;

    /** @var string The resource name of the Corpus. */
    private string $name;

    /** @var bool|null If set to true, any Documents and objects related to this Corpus will also be deleted. */
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
        $this->validateResource(ResourceType::Corpus, $this->name);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->name";
    }

    public function defaultQuery(): array
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
