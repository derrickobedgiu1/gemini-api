<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class ContentEmbedding
{
    /**
     * @param float[] $values The embedding values.
     */
    public function __construct(public readonly array $values)
    {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            values: $data['values'],
        );
    }

    public function toArray(): array
    {
        return [
            'values' => $this->values,
        ];
    }
}
