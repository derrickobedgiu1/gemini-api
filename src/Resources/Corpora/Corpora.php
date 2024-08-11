<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Resources\Corpora;

use Derrickob\GeminiApi\Contracts\Resources\Corpora\CorporaContract;
use Derrickob\GeminiApi\Data\Corpus;
use Derrickob\GeminiApi\Data\MetadataFilter;
use Derrickob\GeminiApi\Requests\Corpora\CreateCorporaRequest;
use Derrickob\GeminiApi\Requests\Corpora\DeleteCorporaRequest;
use Derrickob\GeminiApi\Requests\Corpora\GetCorporaRequest;
use Derrickob\GeminiApi\Requests\Corpora\ListCorporaRequest;
use Derrickob\GeminiApi\Requests\Corpora\PatchCorporaRequest;
use Derrickob\GeminiApi\Requests\Corpora\QueryCorporaRequest;
use Derrickob\GeminiApi\Resources\Corpora\Documents\Documents;
use Derrickob\GeminiApi\Responses\Corpora\ListCorporaResponse;
use Derrickob\GeminiApi\Responses\Corpora\QueryCorporaResponse;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\BaseResource;

final class Corpora extends BaseResource implements CorporaContract
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
    public function create(Corpus $corpus): Corpus
    {
        /** @var Corpus */
        return $this->connector->send(new CreateCorporaRequest($corpus))->dtoOrFail();
    }

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
    public function delete(array $parameters): bool
    {
        /** @var bool */
        return $this->connector->send(new DeleteCorporaRequest($parameters))->successful();
    }

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
    public function get(string $name): Corpus
    {
        /** @var Corpus */
        return $this->connector->send(new GetCorporaRequest($name))->dtoOrFail();
    }

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
    public function list(array $parameters = []): ListCorporaResponse
    {
        /** @var ListCorporaResponse */
        return $this->connector->send(new ListCorporaRequest($parameters))->dtoOrFail();
    }

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
    public function patch(array $parameters): Corpus
    {
        /** @var Corpus */
        return $this->connector->send(new PatchCorporaRequest($parameters))->dtoOrFail();
    }

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
    public function query(array $parameters): QueryCorporaResponse
    {
        /** @var QueryCorporaResponse */
        return $this->connector->send(new QueryCorporaRequest($parameters))->dtoOrFail();
    }

    public function documents(): Documents
    {
        return new Documents($this->connector);
    }

    public function permissions(): CorporaPermissions
    {
        return new CorporaPermissions($this->connector);
    }
}
