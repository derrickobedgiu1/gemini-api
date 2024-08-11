<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Resources;

use Derrickob\GeminiApi\Contracts\Resources\MediaContract;
use Derrickob\GeminiApi\Data\File;
use Derrickob\GeminiApi\Requests\Media\InitiateMediaUploadRequest;
use Derrickob\GeminiApi\Requests\Media\UploadMediaChunkRequest;
use Derrickob\GeminiApi\Responses\Media\UploadMediaChunkResponse;
use Exception;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\BaseResource;

final class Media extends BaseResource implements MediaContract
{
    protected const CHUNK_SIZE = 10 * 1024 * 1024;

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
    public function upload(string $filePath, ?File $fileMetadata = null): UploadMediaChunkResponse
    {
        if (!file_exists($filePath)) {
            throw new Exception('File does not exist. Provide a valid file path');
        }

        $fileSize = filesize($filePath);
        if ($fileSize === false) {
            throw new Exception('Unable to determine file size');
        }

        $mimeType = mime_content_type($filePath);
        if ($mimeType === false) {
            throw new Exception('Unable to determine MIME type');
        }

        $fileMetadata ??= new File();
        $fileMetadata->sizeBytes = (string)$fileSize;
        $fileMetadata->mimeType = $mimeType;

        $response = $this->connector->send(new InitiateMediaUploadRequest($fileMetadata));

        if ($response->failed()) {
            throw new Exception('Failed to initiate upload: ' . $response->body());
        }

        $uploadUrl = $response->header('X-Goog-Upload-URL');

        if (!is_string($uploadUrl) || $uploadUrl === '') {
            throw new Exception('Failed to get upload URL from initial request.');
        }

        $handle = fopen($filePath, 'rb');
        if ($handle === false) {
            throw new Exception('Failed to open file for reading');
        }

        $chunkSize = self::CHUNK_SIZE;
        $offset = 0;

        try {
            while (!feof($handle)) {
                $chunkData = fread($handle, $chunkSize);
                if ($chunkData === false) {
                    throw new Exception('Failed to read chunk from file');
                }
                $end = $offset + strlen($chunkData);
                $command = ($end < $fileSize) ? 'upload' : 'upload, finalize';
                $chunkRequest = new UploadMediaChunkRequest($uploadUrl, $chunkData, $offset, $command);
                $response = $this->connector->send($chunkRequest);

                if ($response->failed()) {
                    throw new Exception('Chunk upload failed: ' . $response->body());
                }

                $offset = $end;
            }
        } finally {
            fclose($handle);
        }

        /** @var UploadMediaChunkResponse */
        return $response->dtoOrFail();
    }
}
