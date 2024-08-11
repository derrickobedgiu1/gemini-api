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

/**
 * Patch Corpora Document
 */
final class PatchDocumentRequest extends Request implements HasBody
{
    use HasJsonBody;

    use ParameterTypeValidator;
    use ApiEndpointValidator;

    protected Method $method = Method::PATCH;
    public AuthMethod $auth = AuthMethod::ADC;

    /** @var string The Document resource name. */
    private string $name;

    /** @var string The list of fields to update. */
    private string $updateMask;

    /** @var Document The Document resource name. */
    private Document $document;

    /**
     * @param array{
     *     name: string,
     *     updateMask: string,
     *     document: Document,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);

        $this->name = $parameters['name'];
        $this->updateMask = $parameters['updateMask'];
        $this->document = $parameters['document'];
        $this->validateResource(ResourceType::Document, $this->name);
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
        return $this->document->toArray();
    }

    public function createDtoFromResponse(Response $response): Document
    {
        $data = $response->json();

        return Document::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'name' => ['string'],
            'updateMask' => ['string'],
            'document' => [Document::class],
        ];
    }
}
