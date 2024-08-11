<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Resources;

use Derrickob\GeminiApi\Contracts\Resources\FilesContract;
use Derrickob\GeminiApi\Data\File;
use Derrickob\GeminiApi\Requests\Files\DeleteFileRequest;
use Derrickob\GeminiApi\Requests\Files\GetFileRequest;
use Derrickob\GeminiApi\Requests\Files\ListFilesRequest;
use Derrickob\GeminiApi\Responses\Files\ListFilesResponse;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\BaseResource;

final class Files extends BaseResource implements FilesContract
{
    /**
     * Deletes the `File`.
     *
     * @link https://ai.google.dev/api/files#v1beta.files.delete
     *
     * @param string $name The name of the `File` to delete
     *
     * @return bool If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function delete(string $name): bool
    {
        /** @var bool */
        return $this->connector->send(new DeleteFileRequest($name))->successful();
    }

    /**
     * The name of the File to get
     *
     * @link https://ai.google.dev/api/files#v1beta.files.get
     *
     * @return File If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function get(string $name): File
    {
        /** @var File */
        return $this->connector->send(new GetFileRequest($name))->dtoOrFail();
    }

    /**
     * Lists the metadata for `File`s owned by the requesting project.
     *
     * @link https://ai.google.dev/api/files#v1beta.files.list
     *
     * @param array{
     *      pageSize?: int,
     *      pageToken?: string,
     * } $parameters
     *
     * @return ListFilesResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function list(array $parameters = []): ListFilesResponse
    {
        /** @var ListFilesResponse */
        return $this->connector->send(new ListFilesRequest($parameters))->dtoOrFail();
    }
}
