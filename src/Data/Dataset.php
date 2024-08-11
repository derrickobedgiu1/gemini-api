<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class Dataset
{
    /**
     * @param TuningExamples|null $examples Inline examples.
     */
    public function __construct(public ?TuningExamples $examples = null)
    {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            examples: (isset($data['examples'])) ? TuningExamples::fromArray($data['examples']) : null,
        );
    }

    public function toArray(): array
    {
        return ($this->examples instanceof TuningExamples) ? ['examples' => $this->examples->toArray()] : [];
    }
}
