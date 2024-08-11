<?php

namespace Derrickob\GeminiApi\Requests\Bases\Permissions;

use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\ListPermissionsResponse;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Enums\Method;
use Saloon\Http\Response;

abstract class ListPermissionsBaseRequest extends Request
{
    use ParameterTypeValidator;

    protected Method $method = Method::GET;
    public AuthMethod $auth = AuthMethod::ADC;
    protected ?int $pageSize;
    protected ?string $pageToken;
    protected string $parent;

    /**
     * @param array{
     *     parent: string,
     *     pageSize?: int,
     *     pageToken?: string,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);

        $this->parent = $parameters['parent'];
        $this->pageSize = $parameters['pageSize'] ?? null;
        $this->pageToken = $parameters['pageToken'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function resolveEndpoint(): string;

    /**
     * {@inheritdoc}
     */
    public function createDtoFromResponse(Response $response): ListPermissionsResponse
    {
        $data = $response->json();

        return ListPermissionsResponse::fromArray($data);
    }

    /**
     * {@inheritdoc}
     */
    protected function expectParameters(): array
    {
        return [
            'parent' => ['string'],
            'pageSize' => ['int', 'null'],
            'pageToken' => ['string', 'null'],
        ];
    }
}
