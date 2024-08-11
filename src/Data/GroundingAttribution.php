<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class GroundingAttribution
{
    /**
     * @param AttributionSourceId $sourceId Identifier for the source contributing to this attribution.
     * @param Content             $content  Grounding source content that makes up this attribution.
     */
    public function __construct(
        public readonly AttributionSourceId $sourceId,
        public readonly Content             $content,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            sourceId: AttributionSourceId::fromArray($data['sourceId']),
            content: Content::fromArray($data['content']),
        );
    }

    public function toArray(): array
    {
        return [
            'sourceId' => $this->sourceId->toArray(),
            'content' => $this->content->toArray(),
        ];
    }
}
