<?php

namespace Derrickob\GeminiApi\Responses\Corpora\Documents\Chunks;

use Derrickob\GeminiApi\Data\Chunk;

final class BatchUpdateChunkResponse
{
    /**
     * @param Chunk[] $chunks Chunks updated.
     */
    public function __construct(public array $chunks)
    {
        //
    }

    public static function fromArray(array $data): self
    {
        $chunks = array_map(
            static fn (array $chunk): Chunk => Chunk::fromArray($chunk),
            $data['chunks'] ?? [],
        );

        return new self(
            chunks: $chunks
        );
    }

    public function toArray(): array
    {
        return [
            'chunks' => array_map(
                static fn (Chunk $chunk): array => $chunk->toArray(),
                $this->chunks
            ),
        ];
    }
}
