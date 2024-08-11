<?php

namespace Derrickob\GeminiApi\Data;

use Google\Protobuf\ListValue;
use Google\Protobuf\Struct;
use Google\Protobuf\Value;
use InvalidArgumentException;

final class FunctionResponse
{
    /**
     * @param string      $name     The name of the function to call.
     * @param Struct|null $response The function response in JSON object format.
     */
    public function __construct(
        public string $name,
        public ?Struct $response = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['name']) || !is_string($data['name'])) {
            throw new InvalidArgumentException('Name must be a string');
        }

        if (!isset($data['response']) || !is_array($data['response'])) {
            throw new InvalidArgumentException('Response must be an array');
        }

        $responseStruct = new Struct();
        foreach ($data['response'] as $key => $value) {
            $responseStruct->getFields()->offsetSet($key, self::createValue($value));
        }

        return new self(
            name: $data['name'],
            response: $responseStruct
        );
    }

    public function toArray(): array
    {
        $result = [
            'name' => $this->name,
        ];

        if ($this->response instanceof Struct) {
            $fields = $this->response->getFields();
            $result['response'] = [];
            foreach ($fields as $key => $value) {
                if ($value instanceof Value) {
                    $result['response'][$key] = self::valueToNative($value);
                }
            }
        }

        return $result;
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
