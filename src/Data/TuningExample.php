<?php

namespace Derrickob\GeminiApi\Data;

final class TuningExample
{
    /**
     * @param string      $output    The expected model output.
     * @param string|null $textInput Text model input.
     */
    public function __construct(
        public string  $output,
        public ?string $textInput = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            output: $data['output'],
            textInput: $data['textInput'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'output' => $this->output,
            'textInput' => $this->textInput,
        ], fn ($value): bool => $value !== null);
    }
}
