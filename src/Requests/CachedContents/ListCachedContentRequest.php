<?php

namespace Derrickob\GeminiApi\Requests\CachedContents;

use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\CachedContents\ListCachedContentResponse;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Enums\Method;
use Saloon\Http\Response;

final class ListCachedContentRequest extends Request
{
    use ParameterTypeValidator;

    protected Method $method = Method::GET;

    /** @var int|null The maximum number of cached contents to return. */
    private ?int $pageSize;

    /** @var string|null A page token, received from a previous cachedContents.list call. */
    private ?string $pageToken;

    /**
     * @param array{
     *     pageSize?: int,
     *     pageToken?: string,
     * } $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->validateParameters($parameters);

        $this->pageSize = $parameters['pageSize'] ?? null;
        $this->pageToken = $parameters['pageToken'] ?? null;
    }

    public function resolveEndpoint(): string
    {
        return "/cachedContents";
    }

    public function defaultQuery(): array
    {
        return array_filter(['pageSize' => $this->pageSize, 'pageToken' => $this->pageToken]);
    }

    public function createDtoFromResponse(Response $response): ListCachedContentResponse
    {
        $data = $response->json();

        return ListCachedContentResponse::fromArray($data);
    }

    public function expectParameters(): array
    {
        return [
            'pageSize' => ['int', 'null'],
            'pageToken' => ['string', 'null'],
        ];
    }
}
