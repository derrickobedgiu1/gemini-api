<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class RelevantChunk
{
    /**
     * @param float $chunkRelevanceScore Chunk relevance to the query.
     * @param Chunk $chunk               Chunk associated with the query.
     */
    public function __construct(
        public float $chunkRelevanceScore,
        public Chunk $chunk,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            chunkRelevanceScore: $data['chunkRelevanceScore'],
            chunk: Chunk::fromArray($data['chunk'])
        );
    }

    public function toArray(): array
    {
        return [
            'chunkRelevanceScore' => $this->chunkRelevanceScore,
            'chunk' => $this->chunk->toArray(),
        ];
    }
}
