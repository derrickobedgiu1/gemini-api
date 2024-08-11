<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses\Corpora\Documents\Chunks;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use Derrickob\GeminiApi\Data\Chunk;
use Exception;

final class ListChunkResponse implements ResponseContract
{
    /**
     * @param Chunk[]     $chunks        An array of Chunk objects.
     * @param string|null $nextPageToken Optional token for retrieving the next page of results.
     */
    public function __construct(
        public array   $chunks,
        public ?string $nextPageToken = null,
    ) {
        //
    }

    /**
     * Create a ListChunkResponse instance from an array of data.
     *
     * @param array $data The raw chunk array from the API response.
     *
     * @return self A new instance of ListChunkResponse.
     *
     * @throws Exception
     */
    public static function fromArray(array $data): self
    {
        $chunks = array_map(
            static fn (array $chunk): Chunk => Chunk::fromArray($chunk),
            $data['chunks'] ?? [],
        );
        $nextPageToken = $data['nextPageToken'] ?? null;

        return new self(
            chunks: $chunks,
            nextPageToken: $nextPageToken
        );
    }

    /**
     * Convert the ListChunkResponse instance to an array.
     *
     * @return array An array representation of the ListChunkResponse.
     */
    public function toArray(): array
    {
        $chunks = [
            'chunks' => array_map(
                static fn (Chunk $chunk): array => $chunk->toArray(),
                $this->chunks
            ),
        ];

        if ($this->nextPageToken !== null) {
            $chunks['nextPageToken'] = $this->nextPageToken;
        }

        return $chunks;
    }
}
