<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use Derrickob\GeminiApi\Data\Candidate;
use Derrickob\GeminiApi\Data\FunctionCall;
use Derrickob\GeminiApi\Data\PromptFeedback;
use Derrickob\GeminiApi\Data\UsageMetadata;
use InvalidArgumentException;

final class GenerateContentResponse implements ResponseContract
{
    /**
     * @param Candidate[]         $candidates     Candidate responses from the model.
     * @param UsageMetadata       $usageMetadata  Metadata on the generation requests' token usage.
     * @param PromptFeedback|null $promptFeedback Returns the prompt's feedback related to the content filters.
     */
    public function __construct(
        public readonly array           $candidates,
        public readonly UsageMetadata   $usageMetadata,
        public readonly ?PromptFeedback $promptFeedback = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $promptFeedback = null;
        if (isset($data['promptFeedback']) && is_array($data['promptFeedback'])) {
            $promptFeedback = PromptFeedback::fromArray($data['promptFeedback']);
        }

        $candidates = [];
        if (isset($data['candidates']) && is_array($data['candidates'])) {
            $candidates = array_map(
                static fn (array $candidate): Candidate => Candidate::fromArray($candidate),
                $data['candidates']
            );
        }

        if (!isset($data['usageMetadata']) || !is_array($data['usageMetadata'])) {
            throw new InvalidArgumentException('UsageMetadata is missing or not an array');
        }

        return new self(
            candidates: $candidates,
            usageMetadata: UsageMetadata::fromArray($data['usageMetadata']),
            promptFeedback: $promptFeedback,
        );
    }

    public function toArray(): array
    {
        $data = [
            'candidates' => array_map(
                static fn (Candidate $candidate): array => $candidate->toArray(),
                $this->candidates
            ),
            'usageMetadata' => $this->usageMetadata->toArray(),
        ];

        if ($this->promptFeedback instanceof PromptFeedback) {
            $data['promptFeedback'] = $this->promptFeedback->toArray();
        }

        return $data;
    }

    /**
     * Retrieves an array of parts.
     *
     * @return array|null The array of parts or null if not available.
     */
    public function parts(): ?array
    {
        return $this->candidates[0]->content->parts ?? null;
    }

    /**
     * Retrieves the text of the first part.
     *
     * @return string|null The text of the first part or null if not available.
     */
    public function text(): ?string
    {
        $parts = $this->parts();

        if ($parts === null) {
            return null;
        }

        if (!isset($parts[0]) || !isset($parts[0]->text)) {
            return null;
        }

        return $parts[0]->text;
    }

    /**
     * Retrieves the functionCall part from a response.
     *
     * @return FunctionCall|null The functionCall part or null if not available.
     */
    public function functionCall(): ?FunctionCall
    {
        $parts = $this->parts();

        if ($parts === null) {
            return null;
        }

        if (!isset($parts[0]) || !isset($parts[0]->functionCall)) {
            return null;
        }

        return $parts[0]->functionCall;
    }
}
