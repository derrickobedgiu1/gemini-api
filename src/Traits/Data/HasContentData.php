<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Traits\Data;

use Derrickob\GeminiApi\Data\Content;
use Derrickob\GeminiApi\Enums\Role;
use InvalidArgumentException;

trait HasContentData
{
    use HasPartData;
    use HasFunctionData;

    public static function createTextContent(string $text, ?string $role = null): Content
    {
        if (isset($role)) {
            $role = Role::from(strtolower($role));
        }

        return new Content([self::createTextPart($text)], $role);
    }

    public static function createBlobContent(string $mimeType, string $data, ?string $role = null): Content
    {
        $role = Role::from(strtolower((string)$role));

        return new Content([self::createBlobPart($mimeType, $data)], $role);
    }

    public static function createFileContent(string $fileUri, ?string $mimeType = null, ?string $role = null): Content
    {
        $role = isset($role) ? Role::from(strtolower($role)) : null;

        return new Content([self::createFilePart($fileUri, $mimeType)], $role);
    }

    public static function createTextWithBlobContent(string $text, string $mimeType, string $data, ?string $role = null): Content
    {
        $role = isset($role) ? Role::from(strtolower($role)) : null;

        return new Content([
            self::createTextPart($text),
            self::createBlobPart($mimeType, $data),
        ], $role);
    }

    public static function createTextWithFileContent(string $text, string $fileUri, ?string $mimeType = null, ?string $role = null): Content
    {
        $role = isset($role) ? Role::from(strtolower($role)) : null;

        return new Content([
            self::createTextPart($text),
            self::createFilePart($fileUri, $mimeType),
        ], $role);
    }

    public static function createBlobWithFileContent(string $blobMimeType, string $blobData, string $fileUri, ?string $fileMimeType = null, ?string $role = null): Content
    {
        $role = isset($role) ? Role::from(strtolower($role)) : null;

        return new Content([
            self::createBlobPart($blobMimeType, $blobData),
            self::createFilePart($fileUri, $fileMimeType),
        ], $role);
    }

    public static function createTextWithBlobsContent(string $text, array $blobs, ?string $role = null): Content
    {
        $role = isset($role) ? Role::from(strtolower($role)) : null;
        $parts = [self::createTextPart($text)];

        foreach ($blobs as $blob) {
            if (!isset($blob['mimeType']) || !isset($blob['data'])) {
                throw new InvalidArgumentException('Each blob must have mimeType and data');
            }
            $parts[] = self::createBlobPart($blob['mimeType'], $blob['data']);
        }

        return new Content($parts, $role);
    }

    public static function createTextWithFilesContent(string $text, array $files, ?string $role = null): Content
    {
        $role = isset($role) ? Role::from(strtolower($role)) : null;
        $parts = [self::createTextPart($text)];

        foreach ($files as $file) {
            if (!isset($file['fileUri'])) {
                throw new InvalidArgumentException('Each file must have fileUri');
            }
            $parts[] = self::createFilePart($file['fileUri'], $file['mimeType'] ?? null);
        }

        return new Content($parts, $role);
    }

    public static function createBlobsAndFilesContent(array $blobs, array $files, ?string $role = null): Content
    {
        $role = isset($role) ? Role::from(strtolower($role)) : null;
        $parts = [];

        foreach ($blobs as $blob) {
            if (!isset($blob['mimeType']) || !isset($blob['data'])) {
                throw new InvalidArgumentException('Each blob must have mimeType and data');
            }
            $parts[] = self::createBlobPart($blob['mimeType'], $blob['data']);
        }

        foreach ($files as $file) {
            if (!isset($file['fileUri'])) {
                throw new InvalidArgumentException('Each file must have fileUri');
            }
            $parts[] = self::createFilePart($file['fileUri'], $file['mimeType'] ?? null);
        }

        return new Content($parts, $role);
    }

    public static function parseContents(mixed $contents): array
    {
        if ($contents instanceof Content) {
            return $contents->toArray();
        }

        if (is_string($contents)) {
            $content = self::createTextContent($contents);

            return $content->toArray();
        }

        if (is_array($contents)) {
            $parsedContents = [];
            foreach ($contents as $content) {
                if ($content instanceof Content) {
                    $parsedContents[] = $content->toArray();
                } elseif (is_string($content)) {
                    $textContent = self::createTextContent($content);
                    $parsedContents[] = $textContent->toArray();
                } else {
                    throw new InvalidArgumentException('Array contains invalid content type: ' . gettype($content));
                }
            }

            return $parsedContents;
        }

        throw new InvalidArgumentException('Invalid content type: ' . gettype($contents));
    }
}
