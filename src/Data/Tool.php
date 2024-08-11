<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use InvalidArgumentException;

final class Tool
{
    /**
     * @param FunctionDeclaration[]|null $functionDeclarations A list of FunctionDeclarations available to the model that can be used for function calling.
     * @param CodeExecution|null         $codeExecution        Enables the model to execute code as part of generation.
     */
    public function __construct(
        public ?array  $functionDeclarations = null,
        public ?CodeExecution $codeExecution = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $functionDeclarations = null;
        $codeExecution = null;

        if (isset($data['functionDeclarations']) && is_array($data['functionDeclarations'])) {
            $functionDeclarations = array_map(
                static fn (array $function): FunctionDeclaration => FunctionDeclaration::fromArray($function),
                $data['functionDeclarations'],
            );
        }

        if (isset($data['codeExecution'])) {
            $codeExecution = new CodeExecution();
        }

        return new self(
            functionDeclarations: $functionDeclarations,
            codeExecution: $codeExecution,
        );
    }

    public function toArray(): array
    {
        $result = [];

        if ($this->functionDeclarations !== null) {
            $result['functionDeclarations'] = array_map(
                static fn (FunctionDeclaration $functionDeclaration): array => $functionDeclaration->toArray(),
                $this->functionDeclarations
            );
        }

        if ($this->codeExecution instanceof CodeExecution) {
            $result['codeExecution'] = $this->codeExecution->toArray();
        }

        return $result;
    }

    public static function parseTools(array|Tool $tools): array
    {
        if ($tools instanceof Tool) {
            return $tools->toArray();
        }
        $parsedTools = [];
        foreach ($tools as $tool) {
            if ($tool instanceof Tool) {
                $parsedTools[] = $tool->toArray();
            } else {
                throw new InvalidArgumentException('Array can only contain instances of Tools : ' . gettype($tool));
            }
        }

        return $parsedTools;
    }
}
