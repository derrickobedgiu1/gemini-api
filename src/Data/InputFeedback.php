<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use Derrickob\GeminiApi\Enums\BlockReason;

final class InputFeedback
{
    /**
     * @param SafetyRating[]   $safetyRatings Ratings for safety of the input. There is at most one rating per category.
     * @param BlockReason|null $blockReason   If set, the input was blocked and no candidates are returned. Rephrase the input.
     */
    public function __construct(
        public readonly array        $safetyRatings,
        public readonly ?BlockReason $blockReason,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $blockReason = BlockReason::from($data['blockReason']);
        $safetyRatings = array_map(
            static fn (array $rating): SafetyRating => SafetyRating::fromArray($rating),
            $data['safetyRatings'] ?? [],
        );

        return new self(
            safetyRatings: $safetyRatings,
            blockReason: $blockReason
        );
    }

    public function toArray(): array
    {
        $result = [];

        if ($this->safetyRatings !== []) {
            $result['safetyRatings'] = array_map(
                static fn (SafetyRating $rating): array => $rating->toArray(),
                $this->safetyRatings,
            );
        }

        if ($this->blockReason instanceof BlockReason) {
            $result['blockReason'] = $this->blockReason->value;
        }

        return $result;
    }
}
