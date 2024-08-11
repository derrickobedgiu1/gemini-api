<?php

namespace Derrickob\GeminiApi\Requests\TunedModels\Operations;

use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Saloon\Enums\Method;

final class CancelOperationRequest extends Request
{
    use ApiEndpointValidator;

    protected Method $method = Method::POST;
    public AuthMethod $auth = AuthMethod::ADC;

    public function __construct(private readonly string $name)
    {
        $this->validateResource(ResourceType::Operation, $this->name);
    }

    public function resolveEndpoint(): string
    {
        return "https://generativelanguage.googleapis.com/v1/$this->name:cancel";
    }
}
