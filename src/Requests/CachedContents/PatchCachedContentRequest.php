<?php

namespace Derrickob\GeminiApi\Requests\CachedContents;

use Derrickob\GeminiApi\Data\CachedContent;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class PatchCachedContentRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ApiEndpointValidator;
    use ParameterTypeValidator;

    protected Method $method = Method::PATCH;

    /** @var string The resource name referring to the cached content. */
    private string $name;

    /** @var string The list of fields to update. */
    private string $updateMask;

    /** @var CachedContent The content to update. */
    private CachedContent $cachedContent;

    /**
     * @param array{
     *     name: string,
     *     updateMask: string,
     *     cachedContent: CachedContent,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);

        $this->name = $parameters['name'];
        $this->updateMask = $parameters['updateMask'];
        $this->cachedContent = $parameters['cachedContent'];
        $this->validateResource(ResourceType::CachedContent, $this->name);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->name";
    }

    public function defaultQuery(): array
    {
        return ['updateMask' => $this->updateMask];
    }

    protected function defaultBody(): array
    {
        return $this->cachedContent->toArray();
    }

    public function createDtoFromResponse(Response $response): CachedContent
    {
        $data = $response->json();

        return CachedContent::fromArray($data);
    }

    public function expectParameters(): array
    {
        return [
            'name' => ['string'],
            'updateMask' => ['string'],
            'cachedContent' => [CachedContent::class],
        ];
    }
}
