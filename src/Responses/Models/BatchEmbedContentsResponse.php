<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses\Models;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use Derrickob\GeminiApi\Data\ContentEmbedding;

final class BatchEmbedContentsResponse implements ResponseContract
{
    /**
     * @param ContentEmbedding[] $embeddings The embeddings for each request, in the same order as provided in the batch request.
     */
    public function __construct(
        public readonly array $embeddings
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $embeddings = [];

        if (isset($data['embeddings']) && is_array($data['embeddings'])) {
            $embeddings = array_map(
                static fn (array $embedding): ContentEmbedding => ContentEmbedding::fromArray($embedding),
                $data['embeddings']
            );
        }

        return new self(embeddings: $embeddings);
    }

    public function toArray(): array
    {
        return [
            'embeddings' => array_map(
                static fn (ContentEmbedding $contentEmbedding): array => $contentEmbedding->toArray(),
                $this->embeddings
            ),
        ];
    }
}
