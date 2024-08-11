<?php

namespace Derrickob\GeminiApi\Data;

use DateTimeImmutable;
use DateTimeInterface;

final class CachedContent
{
    /**
     * @param string|null            $model             The name of the Model to use for cached content.
     * @param string|null            $name              The resource name referring to the cached content.
     * @param string|null            $displayName       The user-generated meaningful display name of the cached content.
     * @param Content|null           $systemInstruction Developer set system instruction.
     * @param Content[]|null         $contents          The content to cache.
     * @param string|null            $ttl               New TTL for this resource, input only.
     * @param DateTimeImmutable|null $expireTime        Timestamp in UTC of when this resource is considered expired.
     * @param Tool[]|null            $tools             A list of Tools the model may use to generate the next response
     * @param DateTimeImmutable|null $createTime        Creation time of the cache entry.
     * @param DateTimeImmutable|null $updateTime        When the cache entry was last updated in UTC time.
     * @param UsageMetadata|null     $usageMetadata     Metadata on the usage of the cached content.
     * @param ToolConfig|null        $toolConfig        Tool config. This config is shared for all tools.
     */
    public function __construct(
        public ?string            $model = null,
        public ?string            $name = null,
        public ?string            $displayName = null,
        public ?Content           $systemInstruction = null,
        public ?array             $contents = null,
        public ?string            $ttl = null,
        public ?DateTimeImmutable $expireTime = null,
        public ?array             $tools = null,
        public ?DateTimeImmutable $createTime = null,
        public ?DateTimeImmutable $updateTime = null,
        public ?UsageMetadata     $usageMetadata = null,
        public ?ToolConfig        $toolConfig = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $contents = null;
        $tools = null;

        if (isset($data['contents'])) {
            $contents = array_map(
                static fn (array $content): Content => Content::fromArray($content),
                $data['contents'],
            );
        }

        if (isset($data['tools'])) {
            $tools = array_map(
                static fn (array $tool): Tool => Tool::fromArray($tool),
                $data['tools'],
            );
        }

        return new self(
            model: $data['model'] ?? null,
            name: $data['name'] ?? null,
            displayName: $data['displayName'] ?? null,
            systemInstruction: isset($data['systemInstruction']) ? Content::fromArray($data['systemInstruction']) : null,
            contents: $contents,
            ttl: $data['ttl'] ?? null,
            expireTime: isset($data['expireTime']) ? new DateTimeImmutable($data['expireTime']) : null,
            tools: $tools,
            createTime: isset($data['createTime']) ? new DateTimeImmutable($data['createTime']) : null,
            updateTime: isset($data['updateTime']) ? new DateTimeImmutable($data['updateTime']) : null,
            usageMetadata: isset($data['usageMetadata']) ? UsageMetadata::fromArray($data['usageMetadata']) : null,
            toolConfig: isset($data['toolConfig']) ? ToolConfig::fromArray($data['toolConfig']) : null,
        );
    }

    public function toArray(): array
    {
        $result = array_filter([
            'model' => $this->model,
            'name' => $this->name,
            'displayName' => $this->displayName,
            'ttl' => $this->ttl,
        ], fn ($value): bool => $value !== null);

        if ($this->systemInstruction instanceof Content) {
            $result['systemInstruction'] = $this->systemInstruction->toArray();
        }

        if ($this->contents !== null) {
            $result['contents'] = array_map(
                static fn (Content $content): array => $content->toArray(),
                $this->contents,
            );
        }

        if ($this->tools !== null) {
            $result['tools'] = array_map(
                static fn (Tool $tool): array => $tool->toArray(),
                $this->tools,
            );
        }

        if ($this->createTime instanceof DateTimeImmutable) {
            $result['createTime'] = $this->createTime->format(DateTimeInterface::RFC3339_EXTENDED);
        }

        if ($this->updateTime instanceof DateTimeImmutable) {
            $result['updateTime'] = $this->updateTime->format(DateTimeInterface::RFC3339_EXTENDED);
        }

        if ($this->usageMetadata instanceof UsageMetadata) {
            $result['usageMetadata'] = $this->usageMetadata->toArray();
        }

        if ($this->expireTime instanceof DateTimeImmutable) {
            $result['expireTime'] = $this->expireTime->format(DateTimeInterface::RFC3339_EXTENDED);
        }

        if ($this->toolConfig instanceof ToolConfig) {
            $result['toolConfig'] = $this->toolConfig->toArray();
        }

        return $result;
    }
}
