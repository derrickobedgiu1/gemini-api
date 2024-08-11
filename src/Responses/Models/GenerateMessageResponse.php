<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses\Models;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use Derrickob\GeminiApi\Data\Candidate;
use Derrickob\GeminiApi\Data\ContentFilter;
use Derrickob\GeminiApi\Data\Message;

final class GenerateMessageResponse implements ResponseContract
{
    /**
     * @param Message[]       $candidates Candidate response messages from the model.
     * @param Message[]       $messages   The conversation history used by the model.
     * @param ContentFilter[] $filters    A set of content filtering metadata for the prompt and response text.
     */
    public function __construct(
        public readonly array $candidates,
        public readonly array $messages,
        public readonly array $filters,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $candidates = isset($data['candidates']) && is_array($data['candidates'])
            ? array_map(
                static fn (array $candidate): Message => Message::fromArray($candidate),
                $data['candidates']
            )
            : [];

        $messages = isset($data['messages']) && is_array($data['messages'])
            ? array_map(
                static fn (array $message): Message => Message::fromArray($message),
                $data['messages']
            )
            : [];

        $filters = isset($data['filters']) && is_array($data['filters'])
            ? array_map(
                static fn (array $filter): ContentFilter => ContentFilter::fromArray($filter),
                $data['filters']
            )
            : [];

        return new self(
            candidates: $candidates,
            messages: $messages,
            filters: $filters,
        );
    }

    public function toArray(): array
    {
        return [
            'candidates' => array_map(
                static fn (Message $message): array => $message->toArray(),
                $this->candidates
            ),
            'messages' => array_map(
                static fn (Message $message): array => $message->toArray(),
                $this->messages
            ),
            'filters' => array_map(
                static fn (ContentFilter $contentFilter): array => $contentFilter->toArray(),
                $this->filters
            ),
        ];
    }
}
