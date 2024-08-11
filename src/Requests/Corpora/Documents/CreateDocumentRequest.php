<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Corpora\Documents;

use Derrickob\GeminiApi\Data\Document;
use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class CreateDocumentRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ApiEndpointValidator;
    use ParameterTypeValidator;

    protected Method $method = Method::POST;
    public AuthMethod $auth = AuthMethod::ADC;

    /** @var string The name of the Corpus where this Document will be created. */
    private string $parent;

    /** @var Document The field data of the document to create. */
    private Document $document;

    /**
     * @param array{
     *     parent: string,
     *     document: Document,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);

        $this->parent = $parameters['parent'];
        $this->document = $parameters['document'];
        $this->validateResource(ResourceType::Corpus, $this->parent);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->parent/documents";
    }

    public function defaultBody(): array
    {
        return array_filter(['name' => $this->document->name, 'displayName' => $this->document->displayName]);
    }

    public function createDtoFromResponse(Response $response): Document
    {
        $data = $response->json();

        return Document::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'parent' => ['string'],
            'document' => [Document::class],
        ];
    }
}
