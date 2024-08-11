<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\TunedModels;

use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Bases\ContentBaseRequest;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;

final class GenerateTunedContentRequest extends ContentBaseRequest
{
    use ApiEndpointValidator;
    public AuthMethod $auth = AuthMethod::ADC;

    public function resolveEndpoint(): string
    {
        $this->validateResource(ResourceType::TunedModel, $this->model);

        return "/$this->model:generateContent";
    }
}
