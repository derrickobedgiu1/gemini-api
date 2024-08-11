<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Contracts\Resources\Corpora\Documents;

use Derrickob\GeminiApi\Data\Chunk;
use Derrickob\GeminiApi\Responses\Corpora\Documents\Chunks\BatchCreateChunksResponse;
use Derrickob\GeminiApi\Responses\Corpora\Documents\Chunks\BatchUpdateChunkResponse;
use Derrickob\GeminiApi\Responses\Corpora\Documents\Chunks\ListChunkResponse;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

interface DocumentChunksContract
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
    public function batchCreate(array $parameters): BatchCreateChunksResponse;

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
    public function batchDelete(array $parameters): bool;

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
    public function batchUpdate(array $parameters): BatchUpdateChunkResponse;

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
    public function create(array $parameters): Chunk;

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
    public function delete(string $name): bool;

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
    public function get(string $name): Chunk;

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
    public function list(array $parameters): ListChunkResponse;

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
    public function patch(array $parameters): Chunk;
}
