<?php

namespace Derrickob\GeminiApi\Data;

final class SemanticRetrieverConfig
{
    /**
     * @param string                $source                Name of the resource for retrieval.
     * @param Content               $query                 Query to use for matching Chunks in the given resource by similarity.
     * @param MetadataFilter[]|null $metadataFilters       Filters for selecting Documents and/or Chunks from the resource.
     * @param int|null              $maxChunksCount        Maximum number of relevant Chunks to retrieve.
     * @param float|null            $minimumRelevanceScore Minimum relevance score for retrieved relevant Chunks.
     */
    public function __construct(
        public string  $source,
        public Content $query,
        public ?array  $metadataFilters = null,
        public ?int    $maxChunksCount = null,
        public ?float  $minimumRelevanceScore = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $metadataFilters = array_map(
            static fn (array $filter): MetadataFilter => MetadataFilter::fromArray($filter),
            $data['metadataFilters'] ?? [],
        );

        return new self(
            source: $data['source'],
            query: Content::fromArray($data['query']),
            metadataFilters: $metadataFilters,
            maxChunksCount: $data['maxChunksCount'] ?? null,
            minimumRelevanceScore: $data['minimumRelevanceScore'] ?? null,
        );
    }

    public function toArray(): array
    {
        $result = array_filter([
            'source' => $this->source,
            'query' => $this->query->toArray(),
            'maxChunksCount' => $this->maxChunksCount,
            'minimumRelevanceScore' => $this->minimumRelevanceScore,
        ], fn ($value): bool => $value !== null);

        if ($this->metadataFilters !== null && $this->metadataFilters !== []) {
            $result['metadataFilters'] = array_map(
                static fn (MetadataFilter $metadataFilter): array => $metadataFilter->toArray(),
                $this->metadataFilters,
            );
        }

        return $result;
    }
}
