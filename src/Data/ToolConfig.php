<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class ToolConfig
{
    /**
     * @param FunctionCallingConfig|null $functionCallingConfig Function calling config.
     */
    public function __construct(public readonly ?FunctionCallingConfig $functionCallingConfig = null)
    {
        //
    }

    public static function fromArray(array $data): self
    {
        $functionCallingConfig = null;
        if (isset($data['functionCallingConfig']) && is_array($data['functionCallingConfig'])) {
            $functionCallingConfig = FunctionCallingConfig::fromArray($data['functionCallingConfig']);
        }

        return new self(
            functionCallingConfig: $functionCallingConfig
        );
    }

    public function toArray(): array
    {
        $result = [];

        if ($this->functionCallingConfig instanceof FunctionCallingConfig) {
            $result['functionCallingConfig'] = $this->functionCallingConfig->toArray();
        }

        return $result;
    }
}
