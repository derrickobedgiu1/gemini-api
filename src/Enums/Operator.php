<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Enums;

enum Operator: string
{
    case OPERATOR_UNSPECIFIED = 'OPERATOR_UNSPECIFIED';
    case LESS = 'LESS';
    case LESS_EQUAL = 'LESS_EQUAL';
    case EQUAL = 'EQUAL';
    case GREATER_EQUAL = 'GREATER_EQUAL';
    case GREATER = 'GREATER';
    case NOT_EQUAL = 'NOT_EQUAL';
    case INCLUDES = 'INCLUDES';
    case EXCLUDES = 'EXCLUDES';
}
