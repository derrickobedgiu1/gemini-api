<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use DateTimeImmutable;
use DateTimeInterface;
use Derrickob\GeminiApi\Enums\State;
use Exception;

final class Chunk
{
    /**
     * @param ChunkData              $data           The content for the Chunk, such as the text string. The maximum number of tokens per chunk is 2043.
     * @param string|null            $name           The Chunk resource name.
     * @param CustomMetadata[]|null  $customMetadata User provided custom metadata stored as key-value pairs. The maximum number of CustomMetadata per chunk is 20.
     * @param DateTimeImmutable|null $createTime     The Timestamp of when the Chunk was created.
     * @param DateTimeImmutable|null $updateTime     The Timestamp of when the Chunk was last updated.
     * @param State|null             $state          Current state of the Chunk.
     */
    public function __construct(
        public ChunkData          $data,
        public ?string             $name = null,
        public ?array             $customMetadata = null,
        public ?DateTimeImmutable $createTime = null,
        public ?DateTimeImmutable $updateTime = null,
        public ?State             $state = null,
    ) {
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): self
    {
        $customMetadata = null;

        $state = isset($data['state']) ? State::from($data['state']) : null;

        if (isset($data['customMetadata'])) {
            $customMetadata = array_map(
                static fn (array $meta): CustomMetadata => CustomMetadata::fromArray($meta),
                $data['customMetadata'],
            );
        }

        return new self(
            data: ChunkData::fromArray($data['data']),
            name: $data['name'] ?? null,
            customMetadata: $customMetadata,
            createTime: isset($data['createTime']) ? new DateTimeImmutable($data['createTime']) : null,
            updateTime: isset($data['updateTime']) ? new DateTimeImmutable($data['updateTime']) : null,
            state: $state,
        );
    }

    public function toArray(): array
    {
        $result = [
            'data' => $this->data->toArray(),
        ];

        if($this->name !== null) {
            $result['name'] = $this->name;
        }

        if ($this->customMetadata !== null) {
            $result['customMetadata'] = array_map(
                static fn (CustomMetadata $customMetadata): array => $customMetadata->toArray(),
                $this->customMetadata,
            );
        }

        if ($this->createTime instanceof DateTimeImmutable) {
            $result['createTime'] = $this->createTime->format(DateTimeInterface::RFC3339_EXTENDED);
        }

        if ($this->updateTime instanceof DateTimeImmutable) {
            $result['updateTime'] = $this->updateTime->format(DateTimeInterface::RFC3339_EXTENDED);
        }

        if ($this->state instanceof State) {
            $result['state'] = $this->state->value;
        }

        return $result;
    }
}
