<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Contracts\Resources\Corpora\Documents;

use Derrickob\GeminiApi\Data\Document;
use Derrickob\GeminiApi\Data\MetadataFilter;
use Derrickob\GeminiApi\Resources\Corpora\Documents\DocumentChunks;
use Derrickob\GeminiApi\Responses\Corpora\Documents\ListDocumentResponse;
use Derrickob\GeminiApi\Responses\Corpora\Documents\QueryDocumentResponse;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

interface DocumentsContract
{
    /**
     * Creates an empty `Document`.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/documents#v1beta.corpora.documents.create
     *
     * @param array{
     *     parent: string,
     *     document: Document,
     * } $parameters
     *
     * @return Document If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function create(array $parameters): Document;

    /**
     * Deletes a `Document`.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/documents#v1beta.corpora.documents.delete
     *
     * @param array{
     *     name: string,
     *     force?: bool,
     * } $parameters
     *
     * @return bool If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function delete(array $parameters): bool;

    /**
     * Gets information about a specific `Document`.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/documents#v1beta.corpora.documents.get
     *
     * @param string $name The name of the document to retrieve.
     *
     * @return Document If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function get(string $name): Document;

    /**
     * Lists all `Document`s in a Corpus.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/documents#v1beta.corpora.documents.list
     *
     * @param array{
     *     parent: string,
     *     pageSize?: int,
     *     pageToken?: string,
     * } $parameters
     *
     * @return ListDocumentResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function list(array $parameters): ListDocumentResponse;

    /**
     * Updates a `Document`.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/documents#v1beta.corpora.documents.patch
     *
     * @param array{
     *     name: string,
     *     updateMask: string,
     *     document: Document,
     * } $parameters
     *
     * @return Document If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function patch(array $parameters): Document;

    /**
     * Performs semantic search over a `Document`.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/documents#v1beta.corpora.documents.query
     *
     * @param array{
     *     name: string,
     *     query: string,
     *     metadataFilter?: MetadataFilter[]|MetadataFilter,
     *     resultsCount?: int,
     * } $parameters
     *
     * @return QueryDocumentResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function query(array $parameters): QueryDocumentResponse;

    public function chunks(): DocumentChunks;
}
