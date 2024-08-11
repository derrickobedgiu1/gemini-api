<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Resources\Corpora\Documents;

use Derrickob\GeminiApi\Contracts\Resources\Corpora\Documents\DocumentChunksContract;
use Derrickob\GeminiApi\Data\Chunk;
use Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks\BatchCreateChunksRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks\BatchDeleteChunksRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks\BatchUpdateChunkRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks\CreateChunkRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks\DeleteChunkRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks\GetChunkRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks\ListChunkRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks\PatchChunkRequest;
use Derrickob\GeminiApi\Responses\Corpora\Documents\Chunks\BatchCreateChunksResponse;
use Derrickob\GeminiApi\Responses\Corpora\Documents\Chunks\BatchUpdateChunkResponse;
use Derrickob\GeminiApi\Responses\Corpora\Documents\Chunks\ListChunkResponse;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\BaseResource;

final class DocumentChunks extends BaseResource implements DocumentChunksContract
{
    /**
     * Batch create `Chunk`s.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/chunks#v1beta.corpora.documents.chunks.batchCreate
     *
     * @param array{
     *     parent: string,
     *     requests: array{array{chunk: Chunk}},
     * } $parameters
     *
     * @return BatchCreateChunksResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function batchCreate(array $parameters): BatchCreateChunksResponse
    {
        /** @var BatchCreateChunksResponse */
        return $this->connector->send(new BatchCreateChunksRequest($parameters))->dtoOrFail();
    }

    /**
     * Batch delete `Chunk`s.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/chunks#v1beta.corpora.documents.chunks.batchDelete
     *
     * @param array{
     *     parent: string,
     *     requests: string[],
     * } $parameters
     *
     * @return bool If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function batchDelete(array $parameters): bool
    {
        return $this->connector->send(new BatchDeleteChunksRequest($parameters))->successful();
    }

    /**
     * Batch update `Chunk`s.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/chunks#v1beta.corpora.documents.chunks.batchUpdate
     *
     * @param array{
     *     parent: string,
     *     requests: array{
     *         array{
     *             chunk: Chunk,
     *             updateMask: string,
     *         }
     *     }
     * } $parameters
     *
     * @return BatchUpdateChunkResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function batchUpdate(array $parameters): BatchUpdateChunkResponse
    {
        /** @var BatchUpdateChunkResponse */
        return $this->connector->send(new BatchUpdateChunkRequest($parameters))->dtoOrFail();
    }

    /**
     * Creates a `Chunk`.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/chunks#v1beta.corpora.documents.chunks.create
     *
     * @param array{
     *     parent: string,
     *     chunk: Chunk,
     * } $parameters
     *
     * @return Chunk If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function create(array $parameters): Chunk
    {
        /** @var Chunk */
        return $this->connector->send(new CreateChunkRequest($parameters))->dtoOrFail();
    }

    /**
     * Deletes a `Chunk`.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/chunks#v1beta.corpora.documents.chunks.delete
     *
     * @param string $name The resource name of the `Chunk` to delete.
     *
     * @return bool If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function delete(string $name): bool
    {
        /** @var bool */
        return $this->connector->send(new DeleteChunkRequest($name))->successful();
    }

    /**
     * Gets information about a specific `Chunk`.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/chunks#v1beta.corpora.documents.chunks.get
     *
     * @param string $name The name of the Chunk to retrieve.
     *
     * @return Chunk If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function get(string $name): Chunk
    {
        /** @var Chunk */
        return $this->connector->send(new GetChunkRequest($name))->dtoOrFail();
    }

    /**
     * Lists all `Chunk`s in a `Document`.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/chunks#v1beta.corpora.documents.chunks.list
     *
     * @param array{
     *     parent: string,
     *     pageSize?: int,
     *     pageToken?: string,
     * } $parameters
     *
     * @return ListChunkResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function list(array $parameters): ListChunkResponse
    {
        /** @var ListChunkResponse */
        return $this->connector->send(new ListChunkRequest($parameters))->dtoOrFail();
    }

    /**
     * Updates a `Chunk`.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/chunks#v1beta.corpora.documents.chunks.patch
     *
     * @param array{
     *     name: string,
     *     updateMask: string,
     *     chunk: Chunk,
     * } $parameters
     *
     * @return Chunk If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function patch(array $parameters): Chunk
    {
        /** @var Chunk */
        return $this->connector->send(new PatchChunkRequest($parameters))->dtoOrFail();
    }
}
