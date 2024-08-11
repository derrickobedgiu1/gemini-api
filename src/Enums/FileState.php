<?php

namespace Derrickob\GeminiApi\Enums;

/**
 * States for the lifecycle of a File.
 *
 * @link https://ai.google.dev/api/rest/v1beta/files#state
 */
enum FileState: string
{
    /**
     * The default value. This value is used if the state is omitted.
     */
    case STATE_UNSPECIFIED = "STATE_UNSPECIFIED";
    /**
     * File is being processed and cannot be used for inference yet.
     */
    case PROCESSING = "PROCESSING";
    /**
     * File is processed and available for inference.
     */
    case ACTIVE = "ACTIVE";
    /**
     * File failed processing.
     */
    case FAILED = "FAILED";
}
