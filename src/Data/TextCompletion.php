<?php

namespace Derrickob\GeminiApi\Data;

final class TextCompletion
{
    /**
     * @param string                $output           The generated text returned from the model.
     * @param SafetyRating[]        $safetyRatings    Ratings for the safety of a response. There is at most one rating per category.
     * @param CitationMetadata|null $citationMetadata Citation information for model-generated output in this TextCompletion.
     */
    public function __construct(
        public string           $output,
        public array            $safetyRatings,
        public ?CitationMetadata $citationMetadata = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $safetyRatings = array_map(
            static fn (array $rating): SafetyRating => SafetyRating::fromArray($rating),
            $data['safetyRatings'] ?? []
        );

        return new self(
            output: $data['output'],
            safetyRatings: $safetyRatings,
            citationMetadata: isset($data['citationMetadata']) ? CitationMetadata::fromArray($data['citationMetadata']) : null,
        );
    }

    public function toArray(): array
    {
        $result = [
            'output' => $this->output,
            'safetyRatings' => array_map(
                static fn (SafetyRating $safetyRating): array => $safetyRating->toArray(),
                $this->safetyRatings
            ),
        ];

        if ($this->citationMetadata instanceof CitationMetadata) {
            $result['citationMetadata'] = $this->citationMetadata->toArray();
        }

        return $result;
    }
}
