<?php

namespace Derrickob\GeminiApi\Contracts\Resources;

use Derrickob\GeminiApi\Data\File;
use Derrickob\GeminiApi\Responses\Files\ListFilesResponse;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

interface FilesContract
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
    public function delete(string $name): bool;

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
    public function get(string $name): File;

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
    public function list(array $parameters): ListFilesResponse;
}
