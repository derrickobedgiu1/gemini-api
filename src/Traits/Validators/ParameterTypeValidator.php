<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Traits\Validators;

use InvalidArgumentException;

trait ParameterTypeValidator
{
    /**
     * Validates the given parameters against the expected types.
     *
     * @param array<string, mixed> $parameters Parameters to validate
     *
     * @throws InvalidArgumentException If a parameter is missing, unexpected, or of invalid type
     */
    private function validateParameters(array $parameters): void
    {
        $expectedParameters = $this->expectParameters();

        foreach ($expectedParameters as $key => $expectedTypes) {
            if (!in_array('null', $expectedTypes, true) && !array_key_exists($key, $parameters)) {
                throw new InvalidArgumentException("Missing required parameter: $key");
            }
        }

        foreach ($parameters as $key => $value) {
            if (!array_key_exists($key, $expectedParameters)) {
                throw new InvalidArgumentException("Unexpected parameter: $key");
            }

            if (!$this->validateType($value, $expectedParameters[$key])) {
                throw new InvalidArgumentException("Invalid type for parameter '$key'. Expected: " . implode('|', $expectedParameters[$key]));
            }
        }
    }

    /**
     * Validates the type of given value against expected types.
     *
     * @param mixed    $value         The value to validate
     * @param string[] $expectedTypes Array of expected types
     *
     * @return bool True if the value matches any of the expected types, false otherwise
     */
    private function validateType(mixed $value, array $expectedTypes): bool
    {
        foreach ($expectedTypes as $expectedType) {
            if ($expectedType === 'mixed') {
                return true;
            }

            if ($value === null && $expectedType === 'null') {
                return true;
            }

            if ($expectedType === 'bool' && is_bool($value)) {
                return true;
            }

            if ($expectedType === 'int' && is_int($value)) {
                return true;
            }

            if ($expectedType === 'float' && is_float($value)) {
                return true;
            }

            if ($expectedType === 'string' && is_string($value)) {
                return true;
            }

            if ($expectedType === 'array' && is_array($value)) {
                return true;
            }

            if ($expectedType === 'object' && is_object($value)) {
                return true;
            }

            if (is_string($expectedType) && class_exists($expectedType) && $value instanceof $expectedType) {
                return true;
            }

            if (is_string($expectedType) && interface_exists($expectedType) && $value instanceof $expectedType) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the rules to use for validating received parameters.
     *
     * @return array<string, string[]> An array where keys are parameter names and values are arrays of allowed types
     */
    abstract protected function expectParameters(): array;
}
