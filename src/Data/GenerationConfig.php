<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class GenerationConfig
{
    /**
     * @param string[]|null $stopSequences    The set of character sequences (up to 5) that will stop output generation.
     * @param string|null   $responseMimeType MIME type of the generated candidate text.
     * @param Schema|null   $responseSchema   Output schema of the generated candidate text.
     * @param int|null      $candidateCount   Number of generated responses to return.
     * @param int|null      $maxOutputTokens  The maximum number of tokens to include in a response candidate.
     * @param float|null    $temperature      Controls the randomness of the output.
     * @param float|null    $topP             The maximum cumulative probability of tokens to consider when sampling.
     * @param int|null      $topK             The maximum number of tokens to consider when sampling.
     */
    public function __construct(
        public ?array  $stopSequences = null,
        public ?string $responseMimeType = null,
        public ?Schema $responseSchema = null,
        public ?int    $candidateCount = null,
        public ?int    $maxOutputTokens = null,
        public ?float  $temperature = null,
        public ?float  $topP = null,
        public ?int    $topK = null
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $responseSchema = null;
        if (isset($data['responseSchema']) && is_array($data['responseSchema'])) {
            $responseSchema = Schema::fromArray($data['responseSchema']);
        }

        return new self(
            stopSequences: $data['stopSequences'] ?? null,
            responseMimeType: $data['responseMimeType'] ?? null,
            responseSchema: $responseSchema,
            candidateCount: $data['candidateCount'] ?? null,
            maxOutputTokens: $data['maxOutputTokens'] ?? null,
            temperature: $data['temperature'] ?? null,
            topP: $data['topP'] ?? null,
            topK: $data['topK'] ?? null
        );
    }

    public function toArray(): array
    {
        $result = array_filter([
            'stopSequences' => $this->stopSequences,
            'responseMimeType' => $this->responseMimeType,
            'candidateCount' => $this->candidateCount,
            'maxOutputTokens' => $this->maxOutputTokens,
            'temperature' => $this->temperature,
            'topP' => $this->topP,
            'topK' => $this->topK,
        ], fn ($value): bool => $value !== null);

        if ($this->responseSchema instanceof Schema) {
            $result['responseSchema'] = $this->responseSchema->toArray();
        }

        return $result;
    }
}
