<?php

namespace Derrickob\GeminiApi\Data;

final class GroundingPassages
{
    /**
     * @param GroundingPassage[] $passages List of passages.
     */
    public function __construct(public array $passages)
    {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            passages: array_map(
                static fn (array $passage): GroundingPassage => GroundingPassage::fromArray($passage),
                $data['passages'] ?? [],
            )
        );
    }

    public function toArray(): array
    {
        return [
            'passages' => array_map(
                static fn (GroundingPassage $passage): array => $passage->toArray(),
                $this->passages,
            ),
        ];
    }
}
