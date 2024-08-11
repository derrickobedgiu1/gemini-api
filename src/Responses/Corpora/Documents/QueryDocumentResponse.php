<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses\Corpora\Documents;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use Derrickob\GeminiApi\Data\RelevantChunk;
use Exception;

final class QueryDocumentResponse implements ResponseContract
{
    /**
     * @param RelevantChunk[] $relevantChunks An array of `RelevantChunk` objects.
     */
    public function __construct(
        public array $relevantChunks,
    ) {
        //
    }

    /**
     * Create a QueryDocumentResponse instance from an array of data.
     *
     * @param array $data The raw query array from the API response.
     *
     * @return self A new instance of QueryDocumentResponse.
     *
     * @throws Exception
     */
    public static function fromArray(array $data): self
    {
        $relevantChunks = array_map(
            static fn (array $relevantChunk): RelevantChunk => RelevantChunk::fromArray($relevantChunk),
            $data['relevantChunks'] ?? [],
        );

        return new self(
            relevantChunks: $relevantChunks,
        );
    }

    /**
     * Convert the QueryDocumentResponse instance to an array.
     *
     * @return array An array representation of the QueryDocumentResponse.
     */
    public function toArray(): array
    {
        return [
            'relevantChunks' => array_map(
                static fn (RelevantChunk $relevantChunk): array => $relevantChunk->toArray(),
                $this->relevantChunks
            ),
        ];
    }
}
