<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses\Models;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use Derrickob\GeminiApi\Data\Embedding;

final class BatchEmbedTextResponse implements ResponseContract
{
    /**
     * @param Embedding[] $embeddings The embeddings generated from the input text.
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
                static fn (array $embedding): Embedding => Embedding::fromArray($embedding),
                $data['embeddings']
            );
        }

        return new self(embeddings: $embeddings);
    }

    public function toArray(): array
    {
        return [
            'embeddings' => array_map(
                static fn (Embedding $embedding): array => $embedding->toArray(),
                $this->embeddings
            ),
        ];
    }
}
