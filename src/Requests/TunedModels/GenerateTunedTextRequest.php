<?php

namespace Derrickob\GeminiApi\Requests\TunedModels;

use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Bases\TextBaseRequest;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;

final class GenerateTunedTextRequest extends TextBaseRequest
{
    use ApiEndpointValidator;

    public AuthMethod $auth = AuthMethod::ADC;

    public function resolveEndpoint(): string
    {
        $this->validateResource(ResourceType::TunedModel, $this->model);

        return "/$this->model:generateText";
    }
}
