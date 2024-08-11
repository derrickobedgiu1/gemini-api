<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses\Corpora\Documents;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use Derrickob\GeminiApi\Data\Document;
use Exception;

final class ListDocumentResponse implements ResponseContract
{
    /**
     * @param Document[]  $documents     An array of Documents objects.
     * @param string|null $nextPageToken Optional token for retrieving the next page of results.
     */
    public function __construct(
        public array   $documents,
        public ?string $nextPageToken = null,
    ) {
        //
    }

    /**
     * Create a ListDocumentResponse instance from an array of data.
     *
     * @param array $data The raw documents array from the API response.
     *
     * @return self A new instance of ListDocumentResponse.
     *
     * @throws Exception
     */
    public static function fromArray(array $data): self
    {
        $documents = array_map(
            static fn (array $document): Document => Document::fromArray($document),
            $data['documents'] ?? [],
        );
        $nextPageToken = $data['nextPageToken'] ?? null;

        return new self(
            documents: $documents,
            nextPageToken: $nextPageToken
        );
    }

    /**
     * Convert the ListDocumentResponse instance to an array.
     *
     * @return array An array representation of the ListDocumentResponse.
     */
    public function toArray(): array
    {
        $documents = [
            'documents' => array_map(
                static fn (Document $document): array => $document->toArray(),
                $this->documents
            ),
        ];

        if ($this->nextPageToken !== null) {
            $documents['nextPageToken'] = $this->nextPageToken;
        }

        return $documents;
    }
}
