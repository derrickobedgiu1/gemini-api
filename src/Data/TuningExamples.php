<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class TuningExamples
{
    /**
     * @param TuningExample[] $examples The examples. Example input can be for text or discuss, but all examples in a set must be of the same type.
     */
    public function __construct(public array $examples)
    {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            examples: array_map(
                static fn (array $example): TuningExample => TuningExample::fromArray($example),
                $data['examples'],
            )
        );
    }

    public function toArray(): array
    {
        return [
            'examples' => array_map(
                static fn (TuningExample $tuningExample): array => $tuningExample->toArray(),
                $this->examples
            ),
        ];
    }
}
