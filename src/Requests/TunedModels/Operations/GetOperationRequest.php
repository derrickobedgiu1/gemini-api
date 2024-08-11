<?php

namespace Derrickob\GeminiApi\Requests\TunedModels\Operations;

use Derrickob\GeminiApi\Data\Operation;
use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Saloon\Enums\Method;
use Saloon\Http\Response;

final class GetOperationRequest extends Request
{
    use ApiEndpointValidator;

    protected Method $method = Method::GET;
    public AuthMethod $auth = AuthMethod::ADC;

    public function __construct(private readonly string $name)
    {
        $this->validateResource(ResourceType::Operation, $this->name);
    }

    public function resolveEndpoint(): string
    {
        return "https://generativelanguage.googleapis.com/v1/$this->name";
    }

    public function createDtoFromResponse(Response $response): Operation
    {
        $data = $response->json();

        return Operation::fromArray($data);
    }
}
