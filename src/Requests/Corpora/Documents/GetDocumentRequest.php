<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Corpora\Documents;

use Derrickob\GeminiApi\Data\Document;
use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Gets Corpus `Document`.
 */
final class GetDocumentRequest extends Request
{
    use ApiEndpointValidator;

    protected Method $method = Method::GET;
    public AuthMethod $auth = AuthMethod::ADC;

    /**
     * @param string $name The name of the document to retrieve.
     */
    public function __construct(private readonly string $name)
    {
        $this->validateResource(ResourceType::Document, $this->name);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->name";
    }

    public function createDtoFromResponse(Response $response): Document
    {
        $data = $response->json();

        return Document::fromArray($data);
    }
}
