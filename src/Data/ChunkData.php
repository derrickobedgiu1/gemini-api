<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class ChunkData
{
    /**
     * @param string $stringValue The Chunk content as a string. The maximum number of tokens per chunk is 2043.
     */
    public function __construct(public string $stringValue)
    {
        //
    }

    /**
     * @param array{
     *     stringValue: string,
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self($data['stringValue']);
    }

    /**
     * @return array{ stringValue: string,}
     */
    public function toArray(): array
    {
        return [
            'stringValue' => $this->stringValue,
        ];
    }
}
