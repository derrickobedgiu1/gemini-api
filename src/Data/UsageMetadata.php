<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class UsageMetadata
{
    /**
     * @param int|null $promptTokenCount        Number of tokens in the prompt. When cachedContent is set, this is still the total effective prompt size.
     * @param int|null $cachedContentTokenCount Number of tokens in the cached part of the prompt.
     * @param int|null $candidatesTokenCount    Total number of tokens across the generated candidates.
     * @param int|null $totalTokenCount         Total token count for the generation request (prompt + candidates).
     */
    public function __construct(
        public readonly ?int $promptTokenCount = null,
        public readonly ?int $cachedContentTokenCount = null,
        public readonly ?int $candidatesTokenCount = null,
        public readonly ?int $totalTokenCount = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            promptTokenCount: $data['promptTokenCount'] ?? null,
            cachedContentTokenCount: $data['cachedContentTokenCount'] ?? null,
            candidatesTokenCount: $data['candidatesTokenCount'] ?? null,
            totalTokenCount: $data['totalTokenCount'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'promptTokenCount' => $this->promptTokenCount,
            'cachedContentTokenCount' => $this->cachedContentTokenCount,
            'candidatesTokenCount' => $this->candidatesTokenCount,
            'totalTokenCount' => $this->totalTokenCount,
        ], fn ($value): bool => $value !== null);
    }
}
