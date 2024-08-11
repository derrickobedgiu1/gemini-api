<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Corpora;

use Derrickob\GeminiApi\Data\MetadataFilter;
use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\Corpora\QueryCorporaResponse;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class QueryCorporaRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ApiEndpointValidator;
    use ParameterTypeValidator;

    protected Method $method = Method::POST;
    public AuthMethod $auth = AuthMethod::ADC;

    /** @var string The name of the Corpus to query. */
    private string $name;

    /** @var string Query string to perform semantic search. */
    private string $queryString;

    /** @var MetadataFilter|MetadataFilter[]|null Filter for Chunk and Document metadata. */
    private mixed $metadataFilters;

    /** @var int|null The maximum number of Chunks to return. */
    private ?int $resultsCount;

    /**
     * @param array{
     *     name: string,
     *     query: string,
     *     metadataFilters?: MetadataFilter[]|MetadataFilter,
     *     resultsCount?: int,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);

        $this->name = $parameters['name'];
        $this->queryString = $parameters['query'];
        $this->metadataFilters = $parameters['metadataFilters'] ?? null;
        $this->resultsCount = $parameters['resultsCount'] ?? null;
        $this->validateResource(ResourceType::Corpus, $this->name);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->name:query";
    }

    public function defaultBody(): array
    {
        $body = [
            'query' => $this->queryString,
        ];

        if ($this->metadataFilters !== null) {
            $body['metadataFilters'] = [MetadataFilter::parseMetadataFilters($this->metadataFilters)];
        }

        if($this->resultsCount !== null) {
            $body['resultsCount'] = $this->resultsCount;
        }

        return $body;
    }

    public function createDtoFromResponse(Response $response): QueryCorporaResponse
    {
        $data = $response->json();

        return QueryCorporaResponse::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'name' => ['string'],
            'query' => ['string'],
            'metadataFilters' => [MetadataFilter::class, 'array', 'null'],
            'resultsCount' => ['int', 'null'],
        ];
    }
}
