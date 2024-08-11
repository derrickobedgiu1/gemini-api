<?php

namespace Derrickob\GeminiApi\Traits\Validators;

use Derrickob\GeminiApi\Enums\ResourceType;
use InvalidArgumentException;

trait ApiEndpointValidator
{
    private static array $validationPatterns = [
        'models' => '/^models\/[^\/]+$/',
        'files' => '/^files\/[^\/]+$/',
        'cachedContents' => '/^cachedContents\/[^\/]+$/',
        'corpora' => '/^corpora\/[^\/]+$/',
        'tunedModels' => '/^tunedModels\/[^\/]+$/',
        'permissions' => [
            'corpora' => '/^corpora\/[^\/]+\/permissions\/[^\/]+$/',
            'tunedModels' => '/^tunedModels\/[^\/]+\/permissions\/[^\/]+$/',
        ],
        'operations' => '/^tunedModels\/[^\/]+\/operations\/[^\/]+$/',
        'documents' => '/^corpora\/[^\/]+\/documents\/[^\/]+$/',
        'chunks' => '/^corpora\/[^\/]+\/documents\/[^\/]+\/chunks\/[^\/]+$/',
    ];

    private function validateResource(ResourceType $resourceType, string $name, ?ResourceType $parentType = null): void
    {
        $pattern = $this->getValidationPattern($resourceType, $parentType);

        if (!preg_match($pattern, $name)) {
            throw new InvalidArgumentException($this->getErrorMessage($resourceType, $parentType));
        }
    }

    private function getValidationPattern(ResourceType $resourceType, ?ResourceType $parentType): string
    {
        $pattern = $this->getPatternForResourceType($resourceType);

        if (is_array($pattern)) {
            if (!$parentType instanceof ResourceType) {
                throw new InvalidArgumentException("Parent resource type is required for {$resourceType->value}");
            }

            return $pattern[$parentType->value] ?? throw new InvalidArgumentException("Invalid parent resource type for {$resourceType->value}");
        }

        return $pattern;
    }

    private function getPatternForResourceType(ResourceType $resourceType): string|array
    {
        return self::$validationPatterns[$resourceType->value]
            ?? throw new InvalidArgumentException("No validation pattern defined for {$resourceType->value}");
    }

    private function getErrorMessage(ResourceType $resourceType, ?ResourceType $parentType): string
    {
        $baseMessage = "Invalid {$resourceType->value} name. Expected format: ";

        return match ($resourceType) {
            ResourceType::Corpus => $baseMessage . "corpora/{your-corpus}",
            ResourceType::TunedModel => $baseMessage . "tunedModels/{your-tuned-model}",
            ResourceType::Permission => $baseMessage . ($parentType === ResourceType::Corpus ?
                    "corpora/{your-corpus}/permissions/{the-permission-id}" :
                    "tunedModels/{your-tuned-model}/permissions/{the-permission-id}"),
            ResourceType::Operation => $baseMessage . "tunedModels/{your-tuned-model}/operations/{the-operation-id}",
            ResourceType::Document => $baseMessage . "corpora/{your-corpus}/documents/{the-document-id}",
            ResourceType::DocumentChunk => $baseMessage . "corpora/{your-corpus}/documents/{the-document}/chunks/{chunk-id}",
            ResourceType::Model => $baseMessage. "models/{model-name}",
            ResourceType::CachedContent => $baseMessage. "cachedContents/{your-cached-content-id}",
            ResourceType::File => $baseMessage. "files/{file-id}",
        };
    }
}
