<?php

namespace Derrickob\GeminiApi\Data;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;

final class TuningSnapshot
{
    /**
     * @param int|null               $step        The tuning step.
     * @param int|null               $epoch       The epoch this step was part of.
     * @param float|null             $meanLoss    The mean loss of the training examples for this step.
     * @param DateTimeImmutable|null $computeTime The timestamp when this metric was computed.
     */
    public function __construct(
        public ?int               $step = null,
        public ?int               $epoch = null,
        public ?float             $meanLoss = null,
        public ?DateTimeImmutable $computeTime = null,
    ) {
        //
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): self
    {
        return new self(
            step: $data['step'] ?? null,
            epoch: $data['epoch'] ?? null,
            meanLoss: $data['meanLoss'] ?? null,
            computeTime: isset($data['computeTime']) ? new DateTimeImmutable($data['computeTime']) : null
        );
    }

    public function toArray(): array
    {
        $result = array_filter([
            'step' => $this->step,
            'epoch' => $this->epoch,
            'meanLoss' => $this->meanLoss,
        ], fn ($value): bool => $value !== null);

        if ($this->computeTime instanceof DateTimeImmutable) {
            $result['computeTime'] = $this->computeTime->format(DateTimeInterface::RFC3339_EXTENDED);
        }

        return $result;
    }
}
