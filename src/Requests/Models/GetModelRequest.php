<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Models;

use Derrickob\GeminiApi\Data\Model;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Saloon\Enums\Method;
use Saloon\Http\Response;

final class GetModelRequest extends Request
{
    use ApiEndpointValidator;

    protected Method $method = Method::GET;

    public function __construct(private readonly string $name)
    {
        $this->validateResource(ResourceType::Model, $name);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->name";
    }

    public function createDtoFromResponse(Response $response): Model
    {
        $data = $response->json();

        return Model::fromArray($data);
    }
}
