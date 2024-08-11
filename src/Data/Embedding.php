<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class Embedding
{
    /**
     * @param float[] $value The embedding values.
     */
    public function __construct(public readonly array $value)
    {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            value: $data['value'],
        );
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
        ];
    }
}
