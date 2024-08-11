<?php

namespace Derrickob\GeminiApi\Enums;

enum Outcome: string
{
    case OUTCOME_UNSPECIFIED = 'OUTCOME_UNSPECIFIED';
    case OUTCOME_OK = 'OUTCOME_OK';
    case OUTCOME_FAILED = 'OUTCOME_FAILED';
    case OUTCOME_DEADLINE_EXCEEDED = 'OUTCOME_DEADLINE_EXCEEDED';
}
