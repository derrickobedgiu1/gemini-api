<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\TunedModels;

use Derrickob\GeminiApi\Data\TunedModel;
use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Saloon\Enums\Method;
use Saloon\Http\Response;

final class GetTunedModelRequest extends Request
{
    use ApiEndpointValidator;
    protected Method $method = Method::GET;
    public AuthMethod $auth = AuthMethod::ADC;

    public function __construct(private readonly string $name)
    {
        $this->validateResource(ResourceType::TunedModel, $this->name);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->name";
    }

    public function createDtoFromResponse(Response $response): TunedModel
    {
        $data = $response->json();

        return TunedModel::fromArray($data);
    }
}
