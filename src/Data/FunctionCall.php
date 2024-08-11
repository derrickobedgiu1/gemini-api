<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use Google\Protobuf\ListValue;
use Google\Protobuf\Struct;
use Google\Protobuf\Value;

final class FunctionCall
{
    /**
     * @param string      $name The name of the function to call.
     * @param Struct|null $args The function parameters and values in JSON object format.
     */
    public function __construct(
        public readonly string $name,
        public ?Struct $args,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $args = null;
        if (isset($data['args']) && is_array($data['args'])) {
            $args = new Struct();
            foreach ($data['args'] as $key => $value) {
                $args->getFields()->offsetSet($key, self::createValue($value));
            }
        }

        return new self(
            name: $data['name'],
            args: $args,
        );
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
        ];

        if ($this->args instanceof Struct) {
            $fields = $this->args->getFields();
            $data['args'] = [];
            foreach ($fields as $key => $value) {
                if ($value instanceof Value) {
                    $data['args'][$key] = self::valueToNative($value);
                }
            }
        }

        return $data;
    }

    private static function createValue(mixed $value): Value
    {
        $protoValue = new Value();
        if (is_null($value)) {
            $protoValue->setNullValue(0);
        } elseif (is_bool($value)) {
            $protoValue->setBoolValue($value);
        } elseif (is_string($value)) {
            $protoValue->setStringValue($value);
        } elseif (is_int($value) || is_float($value)) {
            $protoValue->setNumberValue((float)$value);
        } elseif (is_array($value)) {
            $protoValue->setStructValue(self::createStruct($value));
        }

        return $protoValue;
    }

    private static function createStruct(array $array): Struct
    {
        $struct = new Struct();
        foreach ($array as $key => $value) {
            $struct->getFields()->offsetSet($key, self::createValue($value));
        }

        return $struct;
    }

    private static function valueToNative(Value $value): float|bool|array|string|null
    {
        return match ($value->getKind()) {
            'number_value' => $value->getNumberValue(),
            'string_value' => $value->getStringValue(),
            'bool_value' => $value->getBoolValue(),
            'struct_value' => self::structToArray($value->getStructValue()),
            'list_value' => self::listValueToArray($value->getListValue()),
            default => null,
        };
    }

    private static function listValueToArray(?ListValue $listValue): array
    {
        if (!$listValue instanceof ListValue) {
            return [];
        }

        return array_map(
            fn ($v): float|bool|string|array|null => $v instanceof Value ? self::valueToNative($v) : null,
            iterator_to_array($listValue->getValues())
        );
    }

    private static function structToArray(?Struct $struct): array
    {
        if (!$struct instanceof Struct) {
            return [];
        }

        $result = [];
        foreach ($struct->getFields() as $key => $value) {
            if ($value instanceof Value) {
                $result[$key] = self::valueToNative($value);
            }
        }

        return $result;
    }
}
