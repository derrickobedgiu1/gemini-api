<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses\Models;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use InvalidArgumentException;

final class CountMessageTokensResponse implements ResponseContract
{
    /**
     * @param int $tokenCount The number of tokens that the model tokenizes the prompt into. Always non-negative.
     */
    public function __construct(
        public readonly int $tokenCount
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['tokenCount']) || !is_int($data['tokenCount'])) {
            throw new InvalidArgumentException('tokenCount must be an integer');
        }

        return new self(
            tokenCount: $data['tokenCount'],
        );
    }

    public function toArray(): array
    {
        return [
            'tokenCount' => $this->tokenCount,
        ];
    }
}
