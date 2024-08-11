<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use InvalidArgumentException;

final class MetadataFilter
{
    /**
     * @param string      $key        The key of the metadata to filter on.
     * @param Condition[] $conditions The Conditions for the given key that will trigger this filter. Multiple Conditions are joined by logical ORs.
     */
    public function __construct(
        public string $key,
        public array $conditions,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $conditions = array_map(
            static fn (array $cond): Condition => Condition::fromArray($cond),
            $data['conditions'],
        );

        return new self(
            key: $data['key'],
            conditions: $conditions,
        );
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'conditions' => array_map(
                static fn (Condition $condition): array => $condition->toArray(),
                $this->conditions
            ),
        ];
    }

    public static function parseMetadataFilters(array|MetadataFilter $metadataFilters): array
    {
        if ($metadataFilters instanceof MetadataFilter) {
            return $metadataFilters->toArray();
        }
        $parsedFilters = [];
        foreach ($metadataFilters as $metadataFilter) {
            if ($metadataFilter instanceof SafetySetting) {
                $parsedFilters[] = $metadataFilter->toArray();
            } else {
                throw new InvalidArgumentException('Array can only contain instances of SafetySettings : ' . gettype($metadataFilter));
            }
        }

        return $parsedFilters;
    }
}
