<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\TunedModels\Permissions;

use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Bases\Permissions\CreatePermissionBaseRequest;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;

final class CreatePermissionRequest extends CreatePermissionBaseRequest
{
    use ApiEndpointValidator;

    public function resolveEndpoint(): string
    {
        $this->validateResource(ResourceType::TunedModel, $this->parent);

        return "/$this->parent/permissions";
    }
}
