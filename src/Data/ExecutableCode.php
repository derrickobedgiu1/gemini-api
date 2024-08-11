<?php

namespace Derrickob\GeminiApi\Data;

use Derrickob\GeminiApi\Enums\Language;

final class ExecutableCode
{
    /**
     * @param Language $language Programming language of the code.
     * @param string   $code     The code to be executed.
     */
    public function __construct(
        public Language $language,
        public string   $code,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            language: Language::from($data['language']),
            code: $data['code'],
        );
    }

    public function toArray(): array
    {
        return [
            'language' => $this->language->value,
            'code' => $this->code,
        ];
    }
}
