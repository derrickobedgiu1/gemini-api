<?php

namespace Derrickob\GeminiApi\Data;

final class TextPrompt
{
    /**
     * @param string $text The prompt text.
     */
    public function __construct(public readonly string $text)
    {
        //
    }

    public function toArray(): array
    {
        return ['text' => $this->text];
    }
}
