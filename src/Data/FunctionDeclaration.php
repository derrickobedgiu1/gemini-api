<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use InvalidArgumentException;

final class FunctionDeclaration
{
    /**
     * @param string $name        The name of the function.
     * @param string $description A brief description of the function.
     * @param Schema $parameters  Describes the parameters to this function.
     */
    public function __construct(
        public string $name,
        public string $description,
        public Schema $parameters,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $parameters = Schema::fromArray($data['parameters']);

        return new self(
            name: $data['name'],
            description: $data['description'],
            parameters: $parameters,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'parameters' => $this->parameters->toArray(),
        ];
    }

    /**
     * Generate a function declaration with a simplified interface.
     *
     * @param string $name        The name of the function.
     * @param string $description A brief description of the function.
     * @param array  $params      An array of parameter definitions.
     * @param array  $required    Properties of the function that are optional.
     */
    public static function generate(string $name, string $description, array $params, array $required = []): self
    {
        $properties = [];
        foreach ($params as $paramName => $paramDef) {
            if (!is_array($paramDef) || !isset($paramDef['type'], $paramDef['description'])) {
                throw new InvalidArgumentException("Invalid parameter definition for '{$paramName}'");
            }
            $properties[$paramName] = [
                'type' => strtoupper((string) $paramDef['type']),
                'description' => $paramDef['description'],
            ];
        }

        $parameters = [
            'type' => 'OBJECT',
            'properties' => $properties,
        ];
        $parameters['required'] = $required;

        return new self(
            name: $name,
            description: $description,
            parameters: Schema::fromArray($parameters)
        );
    }
}
