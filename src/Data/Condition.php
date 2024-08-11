<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use Derrickob\GeminiApi\Enums\Operator;

final class Condition
{
    /**
     * @param Operator    $operation    Operator applied to the given key-value pair to trigger the condition.
     * @param string|null $stringValue  The string value to filter the metadata on.
     * @param float|null  $numericValue The numeric value to filter the metadata on.
     */
    public function __construct(
        public readonly Operator $operation,
        public readonly ?string  $stringValue = null,
        public readonly ?float   $numericValue = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            operation: Operator::from($data['operation']),
            stringValue: $data['stringValue'] ?? null,
            numericValue: $data['numericValue'] ?? null,
        );
    }

    /**
     * @return array{
     *     operation: string,
     *     stringValue?: string,
     *     numericValue?: float,
     * }
     */
    public function toArray(): array
    {
        return array_filter([
            'operation' => $this->operation->value,
            'stringValue' => $this->stringValue,
            'numericValue' => $this->numericValue,
        ], fn ($value): bool => $value !== null);
    }
}
