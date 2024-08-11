<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses\Corpora;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use Derrickob\GeminiApi\Data\Corpus;
use Exception;

final class ListCorporaResponse implements ResponseContract
{
    /**
     * @param Corpus[]    $corpora       An array of Corpus objects.
     * @param string|null $nextPageToken Optional token for retrieving the next page of results.
     */
    public function __construct(
        public array   $corpora,
        public ?string $nextPageToken = null,
    ) {
        //
    }

    /**
     * Create a ListCorporaResponse instance from an array of data.
     *
     * @param array $data The raw corpora array from the API response.
     *
     * @return self A new instance of ListCorporaResponse.
     *
     * @throws Exception
     */
    public static function fromArray(array $data): self
    {
        $corpora = array_map(
            static fn (array $corpus): Corpus => Corpus::fromArray(corpus: $corpus),
            $data['corpora'] ?? [],
        );
        $nextPageToken = $data['nextPageToken'] ?? null;

        return new self(
            corpora: $corpora,
            nextPageToken: $nextPageToken,
        );
    }

    /**
     * Convert the ListCorporaResponse instance to an array.
     *
     * @return array An array representation of the ListCorporaResponse.
     */
    public function toArray(): array
    {
        $corpora = [
            'corpora' => array_map(
                static fn (Corpus $corpus): array => $corpus->toArray(),
                $this->corpora
            ),
        ];

        if ($this->nextPageToken !== null) {
            $corpora['nextPageToken'] = $this->nextPageToken;
        }

        return $corpora;
    }
}
