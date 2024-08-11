<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use Derrickob\GeminiApi\Enums\HarmBlockThreshold;
use Derrickob\GeminiApi\Enums\HarmCategory;
use InvalidArgumentException;

final class SafetySetting
{
    /**
     * @param HarmCategory       $category  The category for this setting.
     * @param HarmBlockThreshold $threshold Controls the probability threshold at which harm is blocked.
     */
    public function __construct(
        public readonly HarmCategory       $category,
        public readonly HarmBlockThreshold $threshold,
    ) {
        //
    }

    public static function fromArray(array $array): self
    {
        $category = HarmCategory::from($array['category']);
        $threshold = HarmBlockThreshold::from($array['probability']);

        return new self(
            category: $category,
            threshold: $threshold
        );
    }

    public function toArray(): array
    {
        return [
            'category' => $this->category->value,
            'threshold' => $this->threshold->value,
        ];
    }

    public static function parseSafetySettings(array|SafetySetting $safetySettings): array
    {
        if ($safetySettings instanceof SafetySetting) {
            return $safetySettings->toArray();
        }
        $parsedSettings = [];
        foreach ($safetySettings as $safetySetting) {
            if ($safetySetting instanceof SafetySetting) {
                $parsedSettings[] = $safetySetting->toArray();
            } else {
                throw new InvalidArgumentException('Array can only contain instances of SafetySettings : ' . gettype($safetySetting));
            }
        }

        return $parsedSettings;
    }
}
