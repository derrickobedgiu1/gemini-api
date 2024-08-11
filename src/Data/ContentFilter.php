<?php

namespace Derrickob\GeminiApi\Data;

use Derrickob\GeminiApi\Enums\BlockedReason;

final class ContentFilter
{
    /**
     * @param BlockedReason $reason  A list of reasons why content may have been blocked.
     * @param string        $message A string that describes the filtering behavior in more detail.
     */
    public function __construct(
        public BlockedReason $reason,
        public string        $message,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            reason: BlockedReason::from($data['reason']),
            message: $data['message']
        );
    }

    public function toArray(): array
    {
        return [
            'reason' => $this->reason->value,
            'message' => $this->message,
        ];
    }
}
