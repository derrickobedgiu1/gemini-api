<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Enums;

enum Mode: string
{
    case MODE_UNSPECIFIED = 'MODE_UNSPECIFIED';
    case AUTO = 'AUTO';
    case ANY = 'ANY';
    case NONE = 'NONE';
}
