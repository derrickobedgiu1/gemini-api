<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use Derrickob\GeminiApi\Data\Candidate;
use Derrickob\GeminiApi\Data\ContentFilter;
use Derrickob\GeminiApi\Data\SafetyFeedback;
use Derrickob\GeminiApi\Data\TextCompletion;

final class GenerateTextResponse implements ResponseContract
{
    /**
     * @param TextCompletion[] $candidates     Candidate response messages from the model.
     * @param ContentFilter[]  $filters        A set of content filtering metadata for the prompt and response text.
     * @param SafetyFeedback[] $safetyFeedback Returns any safety feedback related to content filtering.
     */
    public function __construct(
        public readonly array $candidates,
        public readonly array $filters,
        public readonly array $safetyFeedback,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $candidates = isset($data['candidates']) && is_array($data['candidates'])
            ? array_map(
                static fn (array $candidate): TextCompletion => TextCompletion::fromArray($candidate),
                $data['candidates']
            )
            : [];

        $filters = isset($data['filters']) && is_array($data['filters'])
            ? array_map(
                static fn (array $filter): ContentFilter => ContentFilter::fromArray($filter),
                $data['filters']
            )
            : [];

        $safetyFeedbacks = isset($data['safetyFeedback']) && is_array($data['safetyFeedback'])
            ? array_map(
                static fn (array $safetyFeedback): SafetyFeedback => SafetyFeedback::fromArray($safetyFeedback),
                $data['safetyFeedback']
            )
            : [];

        return new self(
            candidates: $candidates,
            filters: $filters,
            safetyFeedback: $safetyFeedbacks,
        );
    }

    public function toArray(): array
    {
        return [
            'candidates' => array_map(
                static fn (TextCompletion $textCompletion): array => $textCompletion->toArray(),
                $this->candidates
            ),
            'filters' => array_map(
                static fn (ContentFilter $contentFilter): array => $contentFilter->toArray(),
                $this->filters
            ),
            'safetyFeedback' => array_map(
                static fn (SafetyFeedback $safetyFeedback): array => $safetyFeedback->toArray(),
                $this->safetyFeedback
            ),
        ];
    }

    public function output(): ?string
    {
        return $this->candidates[0]->output ?? null;
    }
}
