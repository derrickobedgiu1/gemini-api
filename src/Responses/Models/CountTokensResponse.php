<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses\Models;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use InvalidArgumentException;

final class CountTokensResponse implements ResponseContract
{
    /**
     * @param int $totalTokens The number of tokens that the Model tokenizes the prompt into.
     */
    public function __construct(
        public readonly int $totalTokens
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['totalTokens']) || !is_int($data['totalTokens'])) {
            throw new InvalidArgumentException('totalTokens must be an integer');
        }

        return new self(
            totalTokens: $data['totalTokens'],
        );
    }

    public function toArray(): array
    {
        return [
            'totalTokens' => $this->totalTokens,
        ];
    }
}
