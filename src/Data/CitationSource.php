<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class CitationSource
{
    /**
     * @param int|null    $startIndex Start of segment of the response that is attributed to this source.
     * @param int|null    $endIndex   End of the attributed segment, exclusive.
     * @param string|null $uri        URI that is attributed as a source for a portion of the text.
     * @param string|null $license    License for the GitHub project that is attributed as a source for segment.
     */
    public function __construct(
        public ?int    $startIndex = null,
        public ?int    $endIndex = null,
        public ?string $uri = null,
        public ?string $license = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['startIndex'] ?? null,
            $data['endIndex'] ?? null,
            $data['uri'] ?? null,
            $data['license'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'startIndex' => $this->startIndex,
            'endIndex' => $this->endIndex,
            'uri' => $this->uri,
            'license' => $this->license,
        ], fn ($value): bool => $value !== null);
    }
}
