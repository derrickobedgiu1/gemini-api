<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\TunedModels\Permissions;

use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Bases\Permissions\PatchPermissionBaseRequest;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;

final class PatchPermissionRequest extends PatchPermissionBaseRequest
{
    use ApiEndpointValidator;

    public function resolveEndpoint(): string
    {
        $this->validateResource(ResourceType::Permission, $this->name, ResourceType::TunedModel);

        return "/$this->name";
    }
}
