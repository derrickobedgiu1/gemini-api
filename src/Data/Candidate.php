<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use Derrickob\GeminiApi\Enums\FinishReason;

final class Candidate
{
    /**
     * @param Content                     $content               Generated content returned from the model.
     * @param FinishReason                $finishReason          The reason why the model stopped generating tokens.
     * @param SafetyRating[]              $safetyRatings         List of ratings for the safety of a response candidate.
     * @param CitationMetadata            $citationMetadata      Citation information for model-generated candidate.
     * @param int|null                    $tokenCount            Token count for this candidate.
     * @param int|null                    $index                 Index of the candidate in the list of response candidates.
     * @param GroundingAttribution[]|null $groundingAttributions Attribution information for sources that contributed to a grounded answer.
     */
    public function __construct(
        public Content          $content,
        public FinishReason     $finishReason,
        public array            $safetyRatings,
        public CitationMetadata $citationMetadata,
        public ?int              $tokenCount = null,
        public ?int             $index = null,
        public ?array           $groundingAttributions = null,
    ) {
        //
    }

    public static function fromArray(array $candidate): self
    {
        $groundingAttributions = null;

        $citationMetadata = isset($candidate['citationMetadata'])
            ? CitationMetadata::fromArray($candidate['citationMetadata'])
            : new CitationMetadata();

        $safetyRatings = array_map(
            static fn (array $rating): SafetyRating => SafetyRating::fromArray($rating),
            $candidate['safetyRatings'] ?? [],
        );

        if (isset($candidate['groundingAttributions'])) {
            $groundingAttributions = array_map(
                static fn (array $groundingAttributions): GroundingAttribution => GroundingAttribution::fromArray($groundingAttributions),
                $candidate['groundingAttributions'],
            );
        }

        return new self(
            content: Content::fromArray($candidate['content']),
            finishReason: FinishReason::from($candidate['finishReason']),
            safetyRatings: $safetyRatings,
            citationMetadata: $citationMetadata,
            tokenCount: $candidate['tokenCount'] ?? null,
            index: $candidate['index'] ?? null,
            groundingAttributions: $groundingAttributions,
        );
    }

    public function toArray(): array
    {
        $result = [
            'content' => $this->content->toArray(),
            'finishReason' => $this->finishReason->value,
            'safetyRatings' => array_map(
                static fn (SafetyRating $safetyRating): array => $safetyRating->toArray(),
                $this->safetyRatings
            ),
            'citationMetadata' => $this->citationMetadata->toArray(),
            'tokenCount' => $this->tokenCount,
            'index' => $this->index,
        ];

        if (isset($this->groundingAttributions)) {
            $result['groundingAttributions'] = array_map(
                static fn (GroundingAttribution $groundingAttributions): array => $groundingAttributions->toArray(),
                $this->groundingAttributions
            );
        }

        return $result;
    }
}
