<?php

namespace Derrickob\GeminiApi\Requests\CachedContents;

use Derrickob\GeminiApi\Data\CachedContent;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Saloon\Enums\Method;
use Saloon\Http\Response;

final class GetCachedContentRequest extends Request
{
    use ApiEndpointValidator;

    protected Method $method = Method::GET;

    /**
     * @param string $name The resource name referring to the content cache entry.
     */
    public function __construct(private readonly string $name)
    {
        $this->validateResource(ResourceType::CachedContent, $name);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->name";
    }

    public function createDtoFromResponse(Response $response): CachedContent
    {
        $data = $response->json();

        return CachedContent::fromArray($data);
    }
}
