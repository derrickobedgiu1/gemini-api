<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Enums;

enum GranteeType: string
{
    case GRANTEE_TYPE_UNSPECIFIED = 'GRANTEE_TYPE_UNSPECIFIED';
    case USER = 'USER';
    case GROUP = 'GROUP';
    case EVERYONE = 'EVERYONE';
}
