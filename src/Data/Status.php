<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class Status
{
    /**
     * @param int                                   $code    The status code, which should be an enum value of google.rpc.Code.
     * @param string                                $message A developer-facing error message in English.
     * @param array<int, array<string, mixed>>|null $details A list of messages that carry the error details.
     *                                                       Each detail is an array with a "@type" field containing a URI identifying the type.
     */
    public function __construct(
        public int    $code,
        public string $message,
        public ?array $details = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            code: $data['code'],
            message: $data['message'],
            details: $data['details'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'code' => $this->code,
            'message' => $this->message,
            'details' => $this->details,
        ], static fn ($value): bool => $value !== null);
    }
}
