<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use Derrickob\GeminiApi\Enums\MimeType;

final class FileData
{
    /**
     * @param string        $fileUri  URI.
     * @param MimeType|null $mimeType The IANA standard MIME type of the source data.
     */
    public function __construct(
        public readonly string $fileUri,
        public readonly ?MimeType $mimeType = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            fileUri: $data['fileUri'],
            mimeType: isset($data['mimeType']) ? MimeType::from($data['mimeType']) : null,
        );
    }

    public function toArray(): array
    {
        return [
            'mimeType' => $this->mimeType?->value,
            'fileUri' => $this->fileUri,
        ];
    }
}
