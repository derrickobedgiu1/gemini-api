<?php

namespace Derrickob\GeminiApi\Enums;

enum AnswerStyle: string
{
    case ANSWER_STYLE_UNSPECIFIED = 'ANSWER_STYLE_UNSPECIFIED';
    case ABSTRACTIVE = 'ABSTRACTIVE';
    case EXTRACTIVE = 'EXTRACTIVE';
    case VERBOSE = 'VERBOSE';
}
