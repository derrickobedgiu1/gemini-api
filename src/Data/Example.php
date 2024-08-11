<?php

namespace Derrickob\GeminiApi\Data;

final class Example
{
    /**
     * @param Message $input  An example of an input Message from the user.
     * @param Message $output An example of what the model should output given the input.
     */
    public function __construct(
        public Message $input,
        public Message $output,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            input: Message::fromArray($data['input']),
            output: Message::fromArray($data['output']),
        );
    }

    public function toArray(): array
    {
        return [
            'input' => $this->input->toArray(),
            'output' => $this->output->toArray(),
        ];
    }
}
