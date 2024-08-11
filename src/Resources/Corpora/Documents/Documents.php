<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Resources\Corpora\Documents;

use Derrickob\GeminiApi\Contracts\Resources\Corpora\Documents\DocumentsContract;
use Derrickob\GeminiApi\Data\Document;
use Derrickob\GeminiApi\Data\MetadataFilter;
use Derrickob\GeminiApi\Requests\Corpora\Documents\CreateDocumentRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\DeleteDocumentRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\GetDocumentRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\ListDocumentRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\PatchDocumentRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\QueryDocumentRequest;
use Derrickob\GeminiApi\Responses\Corpora\Documents\ListDocumentResponse;
use Derrickob\GeminiApi\Responses\Corpora\Documents\QueryDocumentResponse;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\BaseResource;

final class Documents extends BaseResource implements DocumentsContract
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
    public function create(array $parameters): Document
    {
        /** @var Document */
        return $this->connector->send(new CreateDocumentRequest($parameters))->dtoOrFail();
    }

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
    public function delete(array $parameters): bool
    {
        /** @var bool */
        return $this->connector->send(new DeleteDocumentRequest($parameters))->successful();
    }

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
    public function get(string $name): Document
    {
        /** @var Document */
        return $this->connector->send(new GetDocumentRequest($name))->dtoOrFail();
    }

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
    public function list(array $parameters): ListDocumentResponse
    {
        /** @var ListDocumentResponse */
        return $this->connector->send(new ListDocumentRequest($parameters))->dtoOrFail();
    }

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
    public function patch(array $parameters): Document
    {
        /** @var Document */
        return $this->connector->send(new PatchDocumentRequest($parameters))->dtoOrFail();
    }

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
    public function query(array $parameters): QueryDocumentResponse
    {
        /** @var QueryDocumentResponse */
        return $this->connector->send(new QueryDocumentRequest($parameters))->dtoOrFail();
    }

    public function chunks(): DocumentChunks
    {
        return new DocumentChunks($this->connector);
    }
}
