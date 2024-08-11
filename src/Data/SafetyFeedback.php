<?php

namespace Derrickob\GeminiApi\Data;

final class SafetyFeedback
{
    /**
     * @param SafetyRating  $rating  Safety rating evaluated from content.
     * @param SafetySetting $setting Safety settings applied to the request.
     */
    public function __construct(
        public SafetyRating  $rating,
        public SafetySetting $setting,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            rating: SafetyRating::fromArray($data['data']),
            setting: SafetySetting::fromArray($data['setting']),
        );
    }

    public function toArray(): array
    {
        return [
            'rating' => $this->rating->toArray(),
            'setting' => $this->setting->toArray(),
        ];
    }
}
