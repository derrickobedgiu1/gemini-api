<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class StringList
{
    /**
     * @link https://ai.google.dev/api/semantic-retrieval/documents#stringlist
     *
     * @param string[] $values The string values of the metadata to store.
     */
    public function __construct(public array $values)
    {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self($data['values']);
    }

    public function toArray(): array
    {
        return [
            'values' => $this->values,
        ];
    }
}
