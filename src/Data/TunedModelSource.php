<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class TunedModelSource
{
    /**
     * @param string $tunedModel The name of the TunedModel to use as the starting point for training the new model.
     * @param string $baseModel  The name of the base Model this TunedModel was tuned from.
     */
    public function __construct(
        public string $tunedModel,
        public string $baseModel,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            tunedModel: $data['tunedModel'],
            baseModel: $data['baseModel'],
        );
    }

    public function toArray(): array
    {
        return [
            'tunedModel' => $this->tunedModel,
            'baseModel' => $this->baseModel,
        ];
    }
}
