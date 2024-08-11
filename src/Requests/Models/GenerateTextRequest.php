<?php

namespace Derrickob\GeminiApi\Requests\Models;

use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Bases\TextBaseRequest;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;

final class GenerateTextRequest extends TextBaseRequest
{
    use ApiEndpointValidator;

    public function resolveEndpoint(): string
    {
        $this->validateResource(ResourceType::Model, $this->model);

        return "/$this->model:generateText";
    }
}
