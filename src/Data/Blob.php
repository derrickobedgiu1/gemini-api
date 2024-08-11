<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use Derrickob\GeminiApi\Enums\MimeType;

final class Blob
{
    /**
     * @param MimeType $mimeType The IANA standard MIME type of the source data.
     * @param string   $data     Raw bytes for media formats.
     */
    public function __construct(
        public MimeType $mimeType,
        public string   $data,
    ) {
        //
    }

    /**
     * @param array{
     *     mimeType: string,
     *     data: string,
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            mimeType: MimeType::from($data['mimeType']),
            data: $data['data']
        );
    }

    /**
     * @return array{
     *     mimeType: string,
     *     data: string,
     * }
     */
    public function toArray(): array
    {
        return [
            'mimeType' => $this->mimeType->value,
            'data' => $this->data,
        ];
    }
}
