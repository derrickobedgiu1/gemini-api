<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class Model
{
    /**
     * @param string      $name                       The resource name of the Model.
     * @param string      $version                    The version number of the model.
     * @param string      $displayName                The human-readable name of the model.
     * @param string      $description                A short description of the model.
     * @param int         $inputTokenLimit            Maximum number of input tokens allowed for this model.
     * @param int         $outputTokenLimit           Maximum number of output tokens available for this model.
     * @param string[]    $supportedGenerationMethods The model's supported generation methods.
     * @param float|null  $temperature                Controls the randomness of the output.
     * @param float|null  $topP                       For Nucleus sampling.
     * @param int|null    $topK                       For Top-k sampling.
     * @param string|null $baseModelId                The name of the base model, pass this to the generation request.
     */
    public function __construct(
        public string  $name,
        public string  $version,
        public string  $displayName,
        public string  $description,
        public int     $inputTokenLimit,
        public int     $outputTokenLimit,
        public array   $supportedGenerationMethods,
        public ?float  $temperature,
        public ?float  $topP,
        public ?int    $topK,
        public ?string $baseModelId,
    ) {
        //
    }

    public static function fromArray(array $model): self
    {
        return new self(
            $model['name'],
            $model['version'],
            $model['displayName'],
            $model['description'],
            $model['inputTokenLimit'],
            $model['outputTokenLimit'],
            $model['supportedGenerationMethods'],
            $model['temperature'] ?? null,
            $model['topP'] ?? null,
            $model['topK'] ?? null,
            $model['baseModelId'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'version' => $this->version,
            'displayName' => $this->displayName,
            'description' => $this->description,
            'inputTokenLimit' => $this->inputTokenLimit,
            'outputTokenLimit' => $this->outputTokenLimit,
            'supportedGenerationMethods' => $this->supportedGenerationMethods,
            'temperature' => $this->temperature,
            'topP' => $this->topP,
            'topK' => $this->topK,
        ], fn ($value): bool => $value !== null);
    }
}
