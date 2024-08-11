<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class Hyperparameters
{
    /**
     * @param float|null $epochCount             The number of training epochs. An epoch is one pass through the training data. If not set, a default of 5 will be used.
     * @param float|null $batchSize              The batch size hyperparameter for tuning. If not set, a default of 4 or 16 will be used based on the number of training examples.
     * @param float|null $learningRate           The learning rate hyperparameter for tuning.
     * @param float|null $learningRateMultiplier The learning rate multiplier is used to calculate a final learningRate based on the default (recommended) value.
     */
    public function __construct(
        public ?float $epochCount = null,
        public ?float $batchSize = null,
        public ?float $learningRate = null,
        public ?float $learningRateMultiplier = null,
    ) {
        //
    }

    /**
     * @param array{
     *     epochCount?: float,
     *     batchSize?: float,
     *     learningRate?: float,
     *     learningRateMultiplier?: float,
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            epochCount: $data['epochCount'] ?? null,
            batchSize: $data['batchSize'] ?? null,
            learningRate: $data['learningRate'] ?? null,
            learningRateMultiplier: $data['learningRateMultiplier'] ?? null,
        );
    }

    /**
     * @return array{
     *     epochCount?: float,
     *     batchSize?: float,
     *     learningRate?: float,
     *     learningRateMultiplier?: float,
     * }
     */
    public function toArray(): array
    {
        return array_filter([
            'epochCount' => $this->epochCount,
            'batchSize' => $this->batchSize,
            'learningRate' => $this->learningRate,
            'learningRateMultiplier' => $this->learningRateMultiplier,
        ], fn ($value): bool => $value !== null);
    }
}
