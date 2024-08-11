<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Traits\Data;

use Derrickob\GeminiApi\Data\Content;
use Derrickob\GeminiApi\Data\FunctionCall;
use Derrickob\GeminiApi\Data\FunctionResponse;
use Derrickob\GeminiApi\Data\Part;
use Derrickob\GeminiApi\Enums\Role;
use Google\Protobuf\Struct;
use Google\Protobuf\Value;
use InvalidArgumentException;

trait HasFunctionData
{
    /**
     * Create a Content object with a FunctionCall.
     *
     * @param string                $name The name of the function to call.
     * @param array<string, string> $args An associative array of function arguments.
     * @param string|null           $role The role of the content creator (default is Model).
     *
     * @return Content The created Content object.
     */
    public static function createFunctionCallContent(string $name, array $args, ?string $role = null): Content
    {
        $role = isset($role) ? Role::from(strtolower($role)) : Role::Model;

        $struct = new Struct();
        foreach ($args as $key => $value) {
            $structValue = new Value();
            $structValue->setStringValue($value);
            $struct->getFields()[$key] = $structValue;
        }

        $functionCall = new FunctionCall(name: $name, args: $struct);
        $part = new Part(functionCall: $functionCall);

        return new Content([$part], $role);
    }

    /**
     * Create a Content object with a FunctionResponse.
     *
     * @param string               $name     The name of the function that was called.
     * @param array<string, mixed> $response The response data from the function call.
     * @param string|null          $role     The role of the content creator (default is Function).
     *
     * @return Content The created Content object.
     */
    public static function createFunctionResponseContent(string $name, array $response, ?string $role = null): Content
    {
        $role = isset($role) ? Role::from(strtolower($role)) : Role::Function;

        $struct = new Struct();
        $struct->getFields()['name'] = self::createValue($name);
        $struct->getFields()['content'] = self::createValue($response);

        $functionResponse = new FunctionResponse(name: $name, response: $struct);
        $part = new Part(functionResponse: $functionResponse);

        return new Content([$part], $role);
    }

    /**
     * Create a Value object from various data types.
     *
     * @param mixed $data The data to convert into a Value object.
     *
     * @return Value The created Value object.
     *
     * @throws InvalidArgumentException If the data type is not supported.
     */
    private static function createValue(mixed $data): Value
    {
        $value = new Value();

        if (is_array($data)) {
            $struct = new Struct();
            foreach ($data as $key => $item) {
                $struct->getFields()[$key] = self::createValue($item);
            }
            $value->setStructValue($struct);
        } elseif (is_bool($data)) {
            $value->setBoolValue($data);
        } elseif (is_int($data) || is_float($data)) {
            $value->setNumberValue($data);
        } elseif (is_string($data)) {
            $value->setStringValue($data);
        } elseif (is_null($data)) {
            $value->setNullValue(0);
        } elseif (is_object($data) && method_exists($data, '__toString')) {
            $value->setStringValue($data->__toString());
        } else {
            throw new InvalidArgumentException('Unsupported data type: ' . gettype($data));
        }

        return $value;
    }
}
