<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class SemanticRetrieverChunk
{
    /**
     * @param string $source Name of the source matching the request's SemanticRetrieverConfig.source.
     * @param string $chunk  Name of the Chunk containing the attributed text.
     */
    public function __construct(
        public readonly string $source,
        public readonly string $chunk,
    ) {
        //
    }

    /**
     * @param array{
     *     source: string,
     *     chunk: string,
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            source: $data['source'],
            chunk: $data['chunk'],
        );
    }

    /**
     * @return array{
     *     source: string,
     *     chunk: string,
     * }
     */
    public function toArray(): array
    {
        return [
            'source' => $this->source,
            'chunk' => $this->chunk,
        ];
    }
}
