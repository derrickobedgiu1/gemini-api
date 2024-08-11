<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class CitationMetadata
{
    /**
     * @param CitationSource[]|null $citationSources Citations to sources for a specific response.
     */
    public function __construct(public ?array $citationSources = null)
    {
        //
    }

    public static function fromArray(array $data): self
    {
        $citationSources = array_map(
            static fn (array $source): CitationSource => CitationSource::fromArray($source),
            $data['citationSources'] ?? null,
        );

        return new self($citationSources);
    }

    public function toArray(): array
    {
        return [
            'citationSources' => array_map(
                static fn (CitationSource $citationSource): array => $citationSource->toArray(),
                $this->citationSources ?? []
            ),
        ];
    }
}
