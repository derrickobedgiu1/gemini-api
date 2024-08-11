<?php

namespace Derrickob\GeminiApi\Data;

use DateTimeImmutable;
use DateTimeInterface;
use Derrickob\GeminiApi\Enums\FileState;
use Exception;

final class File
{
    /**
     * @param string|null            $name           The `File` resource name.
     * @param string|null            $displayName    The human-readable display name for the `File`.
     * @param string|null            $mimeType       MIME type of the file.
     * @param string|null            $sizeBytes      Size of the file in bytes.
     * @param DateTimeImmutable|null $createTime     The timestamp of when the `File` was created.
     * @param DateTimeImmutable|null $updateTime     The timestamp of when the `File` was last updated.
     * @param DateTimeImmutable|null $expirationTime The timestamp of when the File will be deleted.
     * @param string|null            $sha256Hash     SHA-256 hash of the uploaded bytes.
     * @param string|null            $uri            The uri of the `File`.
     * @param FileState|null         $state          Processing state of the `File`.
     * @param Status|null            $error          Error status if File processing failed.
     * @param VideoMetadata|null     $videoMetadata  Metadata for a video.
     */
    public function __construct(
        public ?string            $name = null,
        public ?string            $displayName = null,
        public ?string            $mimeType = null,
        public ?string            $sizeBytes = null,
        public ?DateTimeImmutable $createTime = null,
        public ?DateTimeImmutable $updateTime = null,
        public ?DateTimeImmutable $expirationTime = null,
        public ?string            $sha256Hash = null,
        public ?string            $uri = null,
        public ?FileState             $state = null,
        public ?Status            $error = null,
        public ?VideoMetadata     $videoMetadata = null,
    ) {
        //
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            displayName: $data['displayName'] ?? null,
            mimeType: $data['mimeType'] ?? null,
            sizeBytes: $data['sizeBytes'] ?? null,
            createTime: isset($data['createTime']) ? new DateTimeImmutable($data['createTime']) : null,
            updateTime: isset($data['updateTime']) ? new DateTimeImmutable($data['updateTime']) : null,
            expirationTime: isset($data['expirationTime']) ? new DateTimeImmutable($data['expirationTime']) : null,
            sha256Hash: $data['sha256Hash'] ?? null,
            uri: $data['uri'] ?? null,
            state: isset($data['state']) ? FileState::from($data['state']) : null,
            error: isset($data['status']) ? Status::fromArray($data['status']) : null,
            videoMetadata: isset($data['videoMetadata']) ? new VideoMetadata($data['videoMetadata']) : null,
        );
    }

    public function toArray(): array
    {
        $result = array_filter([
            'name' => $this->name,
            'displayName' => $this->displayName,
            'mimeType' => $this->mimeType,
            'sizeBytes' => $this->sizeBytes,
            'createTime' => $this->createTime?->format(DateTimeInterface::RFC3339_EXTENDED),
            'updateTime' => $this->updateTime?->format(DateTimeInterface::RFC3339_EXTENDED),
            'expirationTime' => $this->expirationTime?->format(DateTimeInterface::RFC3339_EXTENDED),
            'sha256Hash' => $this->sha256Hash,
            'uri' => $this->uri,
        ], fn ($value): bool => $value !== null);

        if ($this->state instanceof FileState) {
            $result['state'] = $this->state->value;
        }

        if ($this->error instanceof Status) {
            $result['error'] = $this->error->toArray();
        }

        if ($this->videoMetadata instanceof VideoMetadata) {
            $result['videoMetadata'] = $this->videoMetadata->toArray();
        }

        return $result;
    }
}
