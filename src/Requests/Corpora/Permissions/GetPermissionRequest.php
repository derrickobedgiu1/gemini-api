<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Corpora\Permissions;

use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Bases\Permissions\GetPermissionBaseRequest;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;

final class GetPermissionRequest extends GetPermissionBaseRequest
{
    use ApiEndpointValidator;

    public function resolveEndpoint(): string
    {
        $this->validateResource(ResourceType::Permission, $this->name, ResourceType::Corpus);

        return "/$this->name";
    }
}