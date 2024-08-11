<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Corpora;

use Derrickob\GeminiApi\Data\Corpus;
use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Saloon\Enums\Method;
use Saloon\Http\Response;

final class GetCorporaRequest extends Request
{
    use ApiEndpointValidator;

    protected Method $method = Method::GET;
    public AuthMethod $auth = AuthMethod::ADC;

    /**
     * @param string $name The name of the Corpus.
     */
    public function __construct(private readonly string $name)
    {
        $this->validateResource(ResourceType::Corpus, $this->name);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->name";
    }

    public function createDtoFromResponse(Response $response): Corpus
    {
        $data = $response->json();

        return Corpus::fromArray($data);
    }
}
