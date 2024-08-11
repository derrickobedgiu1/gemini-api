<?php

namespace Derrickob\GeminiApi\Requests\Media;

use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\Media\UploadMediaChunkResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasStringBody;
use Saloon\Traits\Plugins\HasTimeout;

final class UploadMediaChunkRequest extends Request implements HasBody
{
    use HasStringBody;
    use HasTimeout;

    protected Method $method = Method::POST;
    protected int $connectTimeout = 60;

    protected int $requestTimeout = 120;

    /**
     * @param string $uploadUrl Url to upload the chunk to.
     * @param string $chunkData File data to upload.
     * @param int    $offset    Chunk offset.
     * @param string $command   Chunk command.
     */
    public function __construct(
        private readonly string $uploadUrl,
        private readonly string $chunkData,
        private readonly int    $offset,
        private readonly string $command
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return $this->uploadUrl;
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Length' => strlen($this->chunkData),
            'X-Goog-Upload-Offset' => $this->offset,
            'X-Goog-Upload-Command' => $this->command,
        ];
    }

    public function defaultBody(): string
    {
        return $this->chunkData;
    }

    public function createDtoFromResponse(Response $response): UploadMediaChunkResponse
    {
        $data = $response->json();

        return UploadMediaChunkResponse::fromArray($data);
    }
}
