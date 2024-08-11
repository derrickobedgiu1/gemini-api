<?php

namespace Derrickob\GeminiApi\Data;

final class Message
{
    /**
     * @param string                $content          The text content of the structured Message.
     * @param string|null           $author           The author of this Message.
     * @param CitationMetadata|null $citationMetadata Citation information for model-generated content in this Message.
     */
    public function __construct(
        public readonly string            $content,
        public readonly ?string           $author = null,
        public readonly ?CitationMetadata $citationMetadata = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $citationMetadata = null;

        if (isset($data['citationMetadata']) && is_array($data['citationMetadata'])) {
            $citationMetadata = CitationMetadata::fromArray($data['citationMetadata']);
        }

        return new self(
            content: $data['content'],
            author: $data['author'] ?? null,
            citationMetadata: $citationMetadata,
        );
    }

    public function toArray(): array
    {
        $result = array_filter([
            'content' => $this->content,
            'author' => $this->author,
        ], fn ($value): bool => $value !== null);

        if($this->citationMetadata instanceof CitationMetadata) {
            $result['citationMetadata'] = $this->citationMetadata->toArray();
        }

        return $result;
    }
}
