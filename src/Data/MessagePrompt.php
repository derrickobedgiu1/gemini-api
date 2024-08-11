<?php

namespace Derrickob\GeminiApi\Data;

final class MessagePrompt
{
    /**
     * @param Message[]      $messages A snapshot of the recent conversation history sorted chronologically.
     * @param string|null    $context  Text that should be provided to the model first to ground the response.
     * @param Example[]|null $examples Examples of what the model should generate. This includes both user input and the response that the model should emulate.
     */
    public function __construct(
        public array   $messages,
        public ?string $context = null,
        public ?array  $examples = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $examples = null;

        $messages = array_map(
            static fn (array $message): Message => Message::fromArray($message),
            $data['messages'],
        );

        if (isset($data['examples']) && is_array($data['examples'])) {
            $examples = array_map(
                static fn (array $example): Example => Example::fromArray($example),
                $data['examples'],
            );
        }

        return new self(
            messages: $messages,
            context: $data['context'] ?? null,
            examples: $examples,
        );
    }

    public function toArray(): array
    {
        $result = [
            'messages' => array_map(
                static fn (Message $message): array => $message->toArray(),
                $this->messages,
            ),
        ];

        if($this->context !== null) {
            $result['context'] = $this->context;
        }

        if($this->examples !== null && $this->examples !== []) {
            $result['examples'] = array_map(
                static fn (Example $example): array => $example->toArray(),
                $this->examples,
            );
        }

        return $result;
    }
}
