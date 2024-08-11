<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Corpora;

use Derrickob\GeminiApi\Data\Corpus;
use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class PatchCorporaRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ParameterTypeValidator;
    use ApiEndpointValidator;

    protected Method $method = Method::PATCH;
    public AuthMethod $auth = AuthMethod::ADC;

    /** @var string The Corpus resource name. */
    private string $name;

    /** @var Corpus The field data to update. */
    private Corpus $corpus;

    /** @var string The list of fields to update. */
    private string $updateMask;

    /**
     * @param array{
     *     name: string,
     *     updateMask: string,
     *     corpus: Corpus
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);

        $this->name = $parameters['name'];
        $this->updateMask = $parameters['updateMask'];
        $this->corpus = $parameters['corpus'];
        $this->validateResource(ResourceType::Corpus, $this->name);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->name";
    }

    public function defaultQuery(): array
    {
        return ['updateMask' => $this->updateMask];
    }

    public function defaultBody(): array
    {
        return $this->corpus->toArray();
    }

    public function createDtoFromResponse(Response $response): Corpus
    {
        $data = $response->json();

        return Corpus::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'name' => ['string'],
            'updateMask' => ['string'],
            'corpus' => [Corpus::class],
        ];
    }
}
