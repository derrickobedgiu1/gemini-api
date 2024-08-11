<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses\Corpora\Documents\Chunks;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use Derrickob\GeminiApi\Data\Chunk;

final class BatchCreateChunksResponse implements ResponseContract
{
    /**
     * @param Chunk[] $chunks Chunks created.
     */
    public function __construct(public array $chunks)
    {
        //
    }

    public static function fromArray(array $data): self
    {
        $chunks = [];

        if (isset($data['chunks']) && is_array($data['chunks'])) {
            $chunks = array_map(
                static fn (array $chunk): Chunk => Chunk::fromArray($chunk),
                $data['chunks']
            );
        }

        return new self(chunks: $chunks);
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
