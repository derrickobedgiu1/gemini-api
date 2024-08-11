<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use DateTimeImmutable;
use DateTimeInterface;

final class Document
{
    /**
     * @param string|null            $name           The unique identifier for the document.
     * @param string|null            $displayName    The human-readable name of the document.
     * @param CustomMetadata[]|null  $customMetadata User provided custom metadata stored as key-value pairs used for querying.
     * @param DateTimeImmutable|null $createTime     The creation time of the document.
     * @param DateTimeImmutable|null $updateTime     The last update time of the document.
     */
    public function __construct(
        public ?string            $name = null,
        public ?string            $displayName = null,
        public ?array             $customMetadata = null,
        public ?DateTimeImmutable $createTime = null,
        public ?DateTimeImmutable $updateTime = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $customMetadata = null;

        if (isset($data['customMetadata']) && is_array($data['customMetadata'])) {
            $customMetadata = array_map(
                static fn (array $meta): CustomMetadata => CustomMetadata::fromArray($meta),
                $data['customMetadata'],
            );
        }

        return new self(
            name: $data['name'] ?? null,
            displayName: $data['displayName'] ?? null,
            customMetadata: $customMetadata,
            createTime: isset($data['createTime']) ? new DateTimeImmutable($data['createTime']) : null,
            updateTime: isset($data['updateTime']) ? new DateTimeImmutable($data['updateTime']) : null,
        );
    }

    public function toArray(): array
    {
        $result = array_filter([
            'name' => $this->name,
            'displayName' => $this->displayName,
            'createTime' => $this->createTime?->format(DateTimeInterface::RFC3339_EXTENDED),
            'updateTime' => $this->updateTime?->format(DateTimeInterface::RFC3339_EXTENDED),
        ], fn ($value): bool => $value !== null);

        if ($this->customMetadata !== null) {
            $result['customMetadata'] = array_map(
                static fn (CustomMetadata $customMetadata): array => $customMetadata->toArray(),
                $this->customMetadata,
            );
        }

        return $result;
    }
}
