<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Contracts;

interface ResponseContract
{
    /**
     * Create an instance from an array of data.
     *
     * @param array<string, mixed> $data Response data in array format.
     */
    public static function fromArray(array $data): self;

    /**
     * Convert the instance to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
