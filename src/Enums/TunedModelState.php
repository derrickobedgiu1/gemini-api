<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Enums;

enum TunedModelState: string
{
    case STATE_UNSPECIFIED = 'STATE_UNSPECIFIED';
    case CREATING = 'CREATING';
    case ACTIVE = 'ACTIVE';
    case FAILED = 'FAILED';
}
