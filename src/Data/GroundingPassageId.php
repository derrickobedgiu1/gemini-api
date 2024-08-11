<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class GroundingPassageId
{
    /**
     * @param string $passageId ID of the passage matching the GenerateAnswerRequest's GroundingPassage.id.
     * @param int    $partIndex Index of the part within the GenerateAnswerRequest's GroundingPassage.content.
     */
    public function __construct(
        public readonly string $passageId,
        public readonly int    $partIndex,
    ) {
        //
    }

    /**
     * @param array{
     *     passageId: string,
     *     partIndex: int,
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            passageId: $data['passageId'],
            partIndex: $data['partIndex'],
        );
    }

    /**
     * @return array{
     *     passageId: string,
     *     partIndex: int,
     * }
     */
    public function toArray(): array
    {
        return [
            'passageId' => $this->passageId,
            'partIndex' => $this->partIndex,
        ];
    }
}
