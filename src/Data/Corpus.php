<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;

final class Corpus
{
    /**
     * @param string|null            $name        The unique identifier for the corpus.
     * @param string|null            $displayName The human-readable name of the corpus.
     * @param DateTimeImmutable|null $createTime  The creation time of the corpus.
     * @param DateTimeImmutable|null $updateTime  The last update time of the corpus.
     */
    public function __construct(
        public ?string            $name = null,
        public ?string            $displayName = null,
        public ?DateTimeImmutable $createTime = null,
        public ?DateTimeImmutable $updateTime = null,
    ) {
        //
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $corpus): self
    {
        return new self(
            name: $corpus['name'] ?? null,
            displayName: $corpus['displayName'] ?? null,
            createTime: isset($corpus['createTime']) ? new DateTimeImmutable($corpus['createTime']) : null,
            updateTime: isset($corpus['updateTime']) ? new DateTimeImmutable($corpus['updateTime']) : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'displayName' => $this->displayName,
            'createTime' => $this->createTime?->format(DateTimeInterface::RFC3339_EXTENDED),
            'updateTime' => $this->updateTime?->format(DateTimeInterface::RFC3339_EXTENDED),
        ], fn ($value): bool => $value !== null);
    }
}
