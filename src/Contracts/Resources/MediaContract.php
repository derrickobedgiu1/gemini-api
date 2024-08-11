<?php

namespace Derrickob\GeminiApi\Contracts\Resources;

use Derrickob\GeminiApi\Data\File;
use Derrickob\GeminiApi\Responses\Media\UploadMediaChunkResponse;
use Exception;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

interface MediaContract
{
    /**
     * Creates a `File`.
     *
     * @link https://ai.google.dev/api/files#v1beta.media.upload
     *
     * @param string    $filePath     Path to the file to upload
     * @param File|null $fileMetadata Metadata for the file to create.
     *
     * @return UploadMediaChunkResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     * @throws Exception
     */
    public function upload(string $filePath, ?File $fileMetadata = null): UploadMediaChunkResponse;
}
