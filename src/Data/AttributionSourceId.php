<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class AttributionSourceId
{
    /**
     * @param GroundingPassageId|null     $groundingPassage       Identifier for an inline passage.
     * @param SemanticRetrieverChunk|null $semanticRetrieverChunk Identifier for a Chunk fetched via Semantic Retriever.
     */
    public function __construct(
        public readonly ?GroundingPassageId     $groundingPassage = null,
        public readonly ?SemanticRetrieverChunk $semanticRetrieverChunk = null,
    ) {
        //
    }

    /**
     * @param array{
     *     groundingPassage?: array{passageId: string, partIndex: int},
     *     semanticRetrieverChunk?: array{source: string, chunk: string}
     * } $data
     */
    public static function fromArray(array $data): self
    {
        $groundingPassage = isset($data['groundingPassage']) ? GroundingPassageId::fromArray($data['groundingPassage']) : null;

        $semanticRetrieverChunk = isset($data['semanticRetrieverChunk']) ? SemanticRetrieverChunk::fromArray($data['semanticRetrieverChunk']) : null;

        return new self(
            groundingPassage: $groundingPassage,
            semanticRetrieverChunk: $semanticRetrieverChunk,
        );
    }

    /**
     * @return array{
     *     groundingPassage?: array{passageId: string, partIndex: int,},
     *     semanticRetrieverChunk?: array{source: string, chunk: string,}
     * }
     */
    public function toArray(): array
    {
        $result = [];

        if ($this->groundingPassage instanceof GroundingPassageId) {
            $result['groundingPassage'] = $this->groundingPassage->toArray();
        }

        if ($this->semanticRetrieverChunk instanceof SemanticRetrieverChunk) {
            $result['semanticRetrieverChunk'] = $this->semanticRetrieverChunk->toArray();
        }

        return $result;
    }
}
