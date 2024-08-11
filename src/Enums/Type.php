<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Enums;

enum Type: string
{
    case TYPE_UNSPECIFIED = 'TYPE_UNSPECIFIED';
    case STRING = 'STRING';
    case NUMBER = 'NUMBER';
    case INTEGER = 'INTEGER';
    case BOOLEAN = 'BOOLEAN';
    case ARRAY = 'ARRAY';
    case OBJECT = 'OBJECT';
}
