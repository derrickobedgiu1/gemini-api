<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Contracts\Resources\Corpora;

use Derrickob\GeminiApi\Data\Corpus;
use Derrickob\GeminiApi\Data\MetadataFilter;
use Derrickob\GeminiApi\Resources\Corpora\CorporaPermissions;
use Derrickob\GeminiApi\Resources\Corpora\Documents\Documents;
use Derrickob\GeminiApi\Responses\Corpora\ListCorporaResponse;
use Derrickob\GeminiApi\Responses\Corpora\QueryCorporaResponse;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

interface CorporaContract
{
    /**
     * Creates an empty `Corpus`.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/corpora#v1beta.corpora.create
     *
     * @return Corpus If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function create(Corpus $corpus): Corpus;

    /**
     * Deletes a `Corpus`.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/corpora#v1beta.corpora.delete
     *
     * @param array{
     *      name: string,
     *      force?: bool,
     *  } $parameters
     *
     * @return bool If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function delete(array $parameters): bool;

    /**
     * Gets information about a specific `Corpus`.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/corpora#v1beta.corpora.get
     *
     * @param string $name The name of the Corpus.
     *
     * @return Corpus If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function get(string $name): Corpus;

    /**
     *Lists all `Corpora` owned by the user.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/corpora#v1beta.corpora.list
     *
     * @param array{
     *     pageSize?: int,
     *     pageToken?: string,
     * } $parameters
     *
     * @return ListCorporaResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function list(array $parameters = []): ListCorporaResponse;

    /**
     * Updates a `Corpus`.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/corpora#v1beta.corpora.patch
     *
     * @param array{
     *     name: string,
     *     updateMask: string,
     *     corpus: Corpus,
     * } $parameters
     *
     * @return Corpus If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function patch(array $parameters): Corpus;

    /**
     * Performs semantic search over a `Corpus`.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/corpora#v1beta.corpora.query
     *
     * @param array{
     *     name: string,
     *     query: string,
     *     metadataFilter?: MetadataFilter[]|MetadataFilter,
     *     resultsCount?: int,
     * } $parameters
     *
     * @return QueryCorporaResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function query(array $parameters): QueryCorporaResponse;

    public function documents(): Documents;

    public function permissions(): CorporaPermissions;
}
