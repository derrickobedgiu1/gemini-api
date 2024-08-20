<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Traits\Data;

use Derrickob\GeminiApi\Data\Blob;
use Derrickob\GeminiApi\Data\FileData;
use Derrickob\GeminiApi\Data\Part;
use Derrickob\GeminiApi\Enums\MimeType;

trait HasPartData
{
    public static function createTextPart(string $text): Part
    {
        return new Part(text: $text);
    }

    public static function createBlobPart(string $mimeType, string $data): Part
    {
        $mimeType = MimeType::from(strtolower($mimeType));

        return new Part(inlineData: new Blob(mimeType: $mimeType, data: $data));
    }

    public static function createFilePart(string $fileUri, ?string $mimeType = null): Part
    {
        $mimeType = isset($mimeType) ? MimeType::from(strtolower($mimeType)) : null;

        return new Part(fileData: new FileData(fileUri: $fileUri, mimeType: $mimeType));
    }
}
