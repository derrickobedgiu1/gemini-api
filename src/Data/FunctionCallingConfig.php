<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use Derrickob\GeminiApi\Enums\Mode;

final class FunctionCallingConfig
{
    /**
     * @param Mode|null     $mode                 Specifies the mode in which function calling should execute.
     * @param string[]|null $allowedFunctionNames A set of function names that, when provided, limits the functions the model will call.
     */
    public function __construct(
        public readonly ?Mode  $mode = null,
        public readonly ?array $allowedFunctionNames = null
    ) {
        //
    }

    /**
     * @param array{
     *     mode?: string,
     *     allowedFunctionNames?: string[],
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            mode: isset($data['mode']) ? Mode::from($data['mode']) : null,
            allowedFunctionNames: $data['allowedFunctionNames'] ?? null
        );
    }

    /**
     * @return array{
     *     mode?: string,
     *     allowedFunctionNames?: string[],
     * }
     */
    public function toArray(): array
    {
        $result = [];

        if ($this->mode instanceof Mode) {
            $result['mode'] = $this->mode->value;
        }

        if ($this->allowedFunctionNames !== null) {
            $result['allowedFunctionNames'] = $this->allowedFunctionNames;
        }

        return $result;
    }
}
