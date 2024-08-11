<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses\Models;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use Derrickob\GeminiApi\Data\Embedding;
use InvalidArgumentException;

final class EmbedTextResponse implements ResponseContract
{
    /**
     * @param Embedding $embedding The embedding generated from the input content.
     */
    public function __construct(
        public readonly Embedding $embedding
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['embedding']) || !is_array($data['embedding'])) {
            throw new InvalidArgumentException('embedding must be an array');
        }

        return new self(
            embedding: Embedding::fromArray($data['embedding']),
        );
    }

    public function toArray(): array
    {
        return [
            'embedding' => $this->embedding->toArray(),
        ];
    }
}
