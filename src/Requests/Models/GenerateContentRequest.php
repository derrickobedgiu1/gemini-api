<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Models;

use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Bases\ContentBaseRequest;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;

final class GenerateContentRequest extends ContentBaseRequest
{
    use ApiEndpointValidator;

    public function resolveEndpoint(): string
    {
        $this->validateResource(ResourceType::Model, $this->model);

        return "/$this->model:generateContent";
    }
}
