<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use Derrickob\GeminiApi\Enums\Type;

final class Schema
{
    /**
     * @param Type                     $type        Data type.
     * @param string|null              $format      The format of the data.
     * @param string|null              $description A brief description of the parameter.
     * @param bool|null                $nullable    Indicates if the value may be null.
     * @param string[]|null            $enum        Possible values of the element of Type::STRING with enum format.
     * @param array<string, self>|null $properties  Properties of Type::OBJECT.
     * @param string[]|null            $required    Required properties of Type::OBJECT.
     * @param self|null                $items       Schema of the elements of Type::ARRAY.
     */
    public function __construct(
        public Type    $type,
        public ?string $format = null,
        public ?string $description = null,
        public ?bool   $nullable = null,
        public ?array  $enum = null,
        public ?array  $properties = null,
        public ?array  $required = null,
        public ?self   $items = null
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $properties = null;
        if (isset($data['properties']) && is_array($data['properties'])) {
            $properties = [];
            foreach ($data['properties'] as $key => $property) {
                $properties[$key] = self::fromArray($property);
            }
        }

        $required = $data['required'] ?? [];
        $propertyKeys = $properties !== null ? array_keys($properties) : [];
        $required = array_filter($required, fn ($requiredProperty): bool => in_array($requiredProperty, $propertyKeys, true));

        $items = null;
        if (isset($data['items']) && is_array($data['items'])) {
            $items = self::fromArray($data['items']);
        }

        return new self(
            type: Type::from(strtoupper((string) $data['type'])),
            format: $data['format'] ?? null,
            description: $data['description'] ?? null,
            nullable: $data['nullable'] ?? null,
            enum: $data['enum'] ?? null,
            properties: $properties,
            required: $required,
            items: $items,
        );
    }

    public function toArray(): array
    {
        $result = array_filter([
            'type' => $this->type->value,
            'format' => $this->format,
            'description' => $this->description,
            'nullable' => $this->nullable,
            'enum' => $this->enum,
        ], fn ($value): bool => $value !== null);

        if ($this->properties !== null) {
            $result['properties'] = array_map(fn (self $schema): array => $schema->toArray(), $this->properties);
        }

        if ($this->required !== null && $this->required !== []) {
            $result['required'] = $this->required;
        }

        if ($this->items instanceof self) {
            $result['items'] = $this->items->toArray();
        }

        return $result;
    }
}
