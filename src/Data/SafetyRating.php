<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use Derrickob\GeminiApi\Enums\HarmCategory;
use Derrickob\GeminiApi\Enums\HarmProbability;

final class SafetyRating
{
    /**
     * @param HarmCategory    $category    The category for this rating.
     * @param HarmProbability $probability The probability of harm for this content.
     * @param bool|null       $blocked     Was this content blocked because of this rating?
     */
    public function __construct(
        public readonly HarmCategory $category,
        public readonly HarmProbability $probability,
        public readonly ?bool $blocked = null,
    ) {
        //
    }

    public static function fromArray(array $array): self
    {
        $category = HarmCategory::from($array['category']);
        $probability = HarmProbability::from($array['probability']);
        $blocked = $array['blocked'] ?? null;

        return new self($category, $probability, $blocked);
    }

    public function toArray(): array
    {
        $result = [
            'category' => $this->category->value,
            'probability' => $this->probability->value,
        ];

        if ($this->blocked !== null) {
            $result['blocked'] = $this->blocked;
        }

        return $result;
    }
}
