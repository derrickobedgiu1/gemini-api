<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Enums;

enum Role: string
{
    case User = 'user';
    case Model = 'model';
    case Function = 'function';
}
