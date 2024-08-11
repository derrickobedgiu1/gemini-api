<?php

namespace Derrickob\GeminiApi\Data;

final class GroundingPassage
{
    /**
     * @param string  $id      Identifier for the passage for attributing this passage in grounded answers.
     * @param Content $content Content of the passage.
     */
    public function __construct(
        public string  $id,
        public Content $content,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            content: Content::fromArray($data['content']),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content->toArray(),
        ];
    }
}
