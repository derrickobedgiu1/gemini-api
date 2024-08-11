<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use DateTimeImmutable;
use DateTimeInterface;
use Derrickob\GeminiApi\Enums\TunedModelState;
use Exception;

final class TunedModel
{
    /**
     * @param TuningTask|null        $tuningTask       The tuning task that creates the tuned model.
     * @param string|null            $name             The tuned model name.
     * @param string|null            $displayName      The name to display for this model in user interfaces.
     * @param string|null            $description      A short description of this model.
     * @param TunedModelState|null   $state            The state of the tuned model.
     * @param DateTimeImmutable|null $createTime       The timestamp when this model was created.
     * @param DateTimeImmutable|null $updateTime       The timestamp when this model was updated.
     * @param TunedModelSource|null  $tunedModelSource TunedModel to use as the starting point for training the new model.
     * @param string|null            $baseModel        The name of the Model to tune.
     * @param float|null             $temperature      Controls the randomness of the output.
     * @param float|null             $topP             For Nucleus sampling.
     * @param int|null               $topK             For Top-k sampling.
     */
    public function __construct(
        public ?string            $name = null,
        public ?string            $displayName = null,
        public ?string            $description = null,
        public ?TunedModelState   $state = null,
        public ?DateTimeImmutable $createTime = null,
        public ?DateTimeImmutable $updateTime = null,
        public ?TuningTask        $tuningTask = null,
        public ?TunedModelSource  $tunedModelSource = null,
        public ?string            $baseModel = null,
        public ?float             $temperature = null,
        public ?float             $topP = null,
        public ?int               $topK = null,
    ) {
        //
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): self
    {
        $tunedModelSource = null;

        if (isset($data['tunedModelSource'])) {
            $tunedModelSource = TunedModelSource::fromArray($data['tunedModelSource']);
        }

        return new self(
            name: $data['name'] ?? null,
            displayName: $data['displayName'] ?? null,
            description: $data['description'] ?? null,
            state: isset($data['state']) ? TunedModelState::from($data['state']) : null,
            createTime: isset($data['createTime']) ? new DateTimeImmutable($data['createTime']) : null,
            updateTime: isset($data['updateTime']) ? new DateTimeImmutable($data['updateTime']) : null,
            tuningTask: isset($data['tuningTask']) ? TuningTask::fromArray($data['tuningTask']) : null,
            tunedModelSource: $tunedModelSource,
            baseModel: $data['baseModel'] ?? null,
            temperature: $data['temperature'] ?? null,
            topP: $data['topP'] ?? null,
            topK: $data['topK'] ?? null,
        );
    }

    public function toArray(): array
    {
        $result = array_filter([
            'name' => $this->name,
            'displayName' => $this->displayName,
            'description' => $this->description,
            'baseModel' => $this->baseModel,
            'temperature' => $this->temperature,
            'topP' => $this->topP,
            'topK' => $this->topK,
        ], fn ($value): bool => $value !== null);

        if ($this->state instanceof TunedModelState) {
            $result['state'] = $this->state->value;
        }

        if ($this->createTime instanceof DateTimeImmutable) {
            $result['createTime'] = $this->createTime->format(DateTimeInterface::RFC3339_EXTENDED);
        }

        if ($this->updateTime instanceof DateTimeImmutable) {
            $result['updateTime'] = $this->updateTime->format(DateTimeInterface::RFC3339_EXTENDED);
        }

        if ($this->tuningTask instanceof TuningTask) {
            $result['tuningTask'] = $this->tuningTask->toArray();
        }

        if ($this->tunedModelSource instanceof TunedModelSource) {
            $result['tunedModelSource'] = $this->tunedModelSource->toArray();
        }

        return $result;
    }
}
