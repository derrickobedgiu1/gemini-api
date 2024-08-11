<?php

namespace Derrickob\GeminiApi\Data;

use Derrickob\GeminiApi\Enums\Outcome;

final class CodeExecutionResult
{
    /**
     * @param Outcome     $outcome Outcome of the code execution.
     * @param string|null $output  Contains stdout when code execution is successful, stderr or other description otherwise.
     */
    public function __construct(
        public Outcome $outcome,
        public ?string $output,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            outcome: Outcome::from($data['outcome']),
            output: $data['output'] ?? null,
        );
    }

    public function toArray(): array
    {
        $result = [
            'outcome' => $this->outcome->value,
        ];

        if ($this->output) {
            $result['output'] = $this->output;
        }

        return $result;
    }
}
