<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class CustomMetadata
{
    /**
     * @param string          $key             The key of the metadata to store.
     * @param string|null     $stringValue     The string value of the metadata to store.
     * @param StringList|null $stringListValue The StringList value of the metadata to store.
     * @param float|null      $numericValue    The numeric value of the metadata to store.
     */
    public function __construct(
        public string      $key,
        public ?string     $stringValue = null,
        public ?StringList $stringListValue = null,
        public ?float      $numericValue = null
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $stringList = null;

        if (isset($data['stringListValue'])) {
            $stringList = StringList::fromArray($data['stringListValue']);
        }

        return new self(
            key: $data['key'],
            stringValue: $data['stringValue'] ?? null,
            stringListValue: $stringList,
            numericValue: $data['numericValue'] ?? null,
        );

    }

    public function toArray(): array
    {
        $result = array_filter([
            'key' => $this->key,
            'stringValue' => $this->stringValue,
            'numericValue' => $this->numericValue,
        ], fn ($value): bool => $value !== null);

        if ($this->stringListValue instanceof StringList) {
            $result['stringListValue'] = $this->stringListValue->toArray();
        }

        return $result;
    }
}
