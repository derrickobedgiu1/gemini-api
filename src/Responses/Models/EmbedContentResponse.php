<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses\Models;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use Derrickob\GeminiApi\Data\ContentEmbedding;
use InvalidArgumentException;

final class EmbedContentResponse implements ResponseContract
{
    /**
     * @param ContentEmbedding $embedding The embedding generated from the input content.
     */
    public function __construct(
        public readonly ContentEmbedding $embedding
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['embedding']) || !is_array($data['embedding'])) {
            throw new InvalidArgumentException('embedding must be an array');
        }

        return new self(
            embedding: ContentEmbedding::fromArray($data['embedding']),
        );
    }

    public function toArray(): array
    {
        return [
            'embedding' => $this->embedding->toArray(),
        ];
    }
}
