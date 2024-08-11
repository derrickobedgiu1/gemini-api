<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;

final class TuningTask
{
    /**
     * @param Dataset|null           $trainingData    The model training data.
     * @param Hyperparameters|null   $hyperparameters Hyperparameters controlling the tuning process. If not provided, default values will be used.
     * @param DateTimeImmutable|null $startTime       The timestamp when tuning this model started.
     * @param DateTimeImmutable|null $completeTime    The timestamp when tuning this model completed.
     * @param TuningSnapshot[]|null  $snapshots       Metrics collected during tuning.
     */
    public function __construct(
        public ?Dataset           $trainingData = null,
        public ?Hyperparameters   $hyperparameters = null,
        public ?DateTimeImmutable $startTime = null,
        public ?DateTimeImmutable $completeTime = null,
        public ?array             $snapshots = [],
    ) {
        //
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): self
    {
        return new self(
            trainingData: isset($data['trainingData']) ? Dataset::fromArray($data['trainingData']) : null,
            hyperparameters: isset($data['hyperparameters']) ? Hyperparameters::fromArray($data['hyperparameters']) : null,
            startTime: isset($data['startTime']) ? new DateTimeImmutable($data['startTime']) : null,
            completeTime: isset($data['completeTime']) ? new DateTimeImmutable($data['completeTime']) : null,
            snapshots: array_map(
                static fn (array $snapshot): TuningSnapshot => TuningSnapshot::fromArray($snapshot),
                $data['snapshots'] ?? [],
            )
        );
    }

    public function toArray(): array
    {
        $result = [];

        if($this->trainingData instanceof Dataset) {
            $result['trainingData'] = $this->trainingData->toArray();
        }

        if ($this->hyperparameters instanceof Hyperparameters) {
            $result['hyperparameters'] = $this->hyperparameters->toArray();
        }

        if ($this->startTime instanceof DateTimeImmutable) {
            $result['startTime'] = $this->startTime->format(DateTimeInterface::RFC3339_EXTENDED);
        }

        if ($this->completeTime instanceof DateTimeImmutable) {
            $result['completeTime'] = $this->completeTime->format(DateTimeInterface::RFC3339_EXTENDED);
        }

        if ($this->snapshots !== null && $this->snapshots !== []) {
            $result['snapshots'] = array_map(
                static fn (TuningSnapshot $tuningSnapshot): array => $tuningSnapshot->toArray(),
                $this->snapshots
            );
        }

        return $result;
    }
}
