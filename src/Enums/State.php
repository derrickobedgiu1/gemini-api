<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Enums;

enum State: string
{
    case STATE_UNSPECIFIED = 'STATE_UNSPECIFIED';
    case STATE_PENDING_PROCESSING = 'STATE_PENDING_PROCESSING';
    case STATE_ACTIVE = 'STATE_ACTIVE';
    case STATE_FAILED = 'STATE_FAILED';
}
