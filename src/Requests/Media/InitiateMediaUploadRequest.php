<?php

namespace Derrickob\GeminiApi\Requests\Media;

use Derrickob\GeminiApi\Data\File;
use Derrickob\GeminiApi\Requests\Request;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

final class InitiateMediaUploadRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param File $file Metadata for the file to create.
     */
    public function __construct(private readonly File $file)
    {
        //
    }

    // Temporary workaround.
    public function resolveEndpoint(): string
    {
        return 'https://generativelanguage.googleapis.com/upload/v1beta/files';
    }

    protected function defaultHeaders(): array
    {
        return [
            'X-Goog-Upload-Protocol' => 'resumable',
            'X-Goog-Upload-Command' => 'start',
        ];
    }

    public function defaultBody(): array
    {
        return [
            'file' => $this->file->toArray(),
        ];
    }
}
