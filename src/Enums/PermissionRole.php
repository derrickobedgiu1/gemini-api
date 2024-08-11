<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Enums;

enum PermissionRole: string
{
    case ROLE_UNSPECIFIED = 'ROLE_UNSPECIFIED';
    case OWNER = 'OWNER';
    case WRITER = 'WRITER';
    case READER = 'READER';
}
