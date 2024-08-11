<?php

namespace Derrickob\GeminiApi\Enums;

enum ResourceType: string
{
    case Corpus = 'corpora';
    case TunedModel = 'tunedModels';
    case Model = 'models';
    case Permission = 'permissions';
    case Operation = 'operations';
    case Document = 'documents';
    case DocumentChunk = 'chunks';
    case CachedContent = 'cachedContents';
    case File = 'files';
}
