<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Enums;

/**
 * A list of reasons why content may have been blocked.
 *
 * @link https://ai.google.dev/api/rest/v1beta/ContentFilter#blockedreason
 */
enum BlockedReason: string
{
    case BLOCKED_REASON_UNSPECIFIED = 'BLOCK_REASON_UNSPECIFIED';
    case SAFETY = 'SAFETY';
    case OTHER = 'OTHER';
}
