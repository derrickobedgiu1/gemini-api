<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\CachedContents;

use Derrickob\GeminiApi\Data\CachedContent;
use Derrickob\GeminiApi\Requests\Request;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class CreateCachedContentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private readonly CachedContent $cachedContent)
    {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/cachedContents";
    }

    public function defaultBody(): array
    {
        return $this->cachedContent->toArray();
    }

    public function createDtoFromResponse(Response $response): CachedContent
    {
        $data = $response->json();

        return CachedContent::fromArray($data);
    }
}
