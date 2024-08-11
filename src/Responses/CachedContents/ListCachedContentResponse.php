<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses\CachedContents;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use Derrickob\GeminiApi\Data\CachedContent;

final class ListCachedContentResponse implements ResponseContract
{
    /**
     * Response with CachedContents list.
     *
     * @param CachedContent[] $cachedContents List of cached contents.
     * @param string|null     $nextPageToken  A token, which can be sent as pageToken to retrieve the next page.
     */
    public function __construct(
        public array $cachedContents,
        public ?string $nextPageToken = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $cachedContents = isset($data['cachedContents']) && is_array($data['cachedContents'])
            ? array_map(
                static fn (array $cachedContent): CachedContent => CachedContent::fromArray($cachedContent),
                $data['cachedContents']
            )
            : [];

        return new self(
            cachedContents: $cachedContents,
            nextPageToken: isset($data['nextPageToken']) && is_string($data['nextPageToken']) ? $data['nextPageToken'] : null
        );
    }

    public function toArray(): array
    {
        $result = [
            'cachedContents' => array_map(
                static fn (CachedContent $cachedContent): array => $cachedContent->toArray(),
                $this->cachedContents
            ),
        ];

        if ($this->nextPageToken !== null) {
            $result['nextPageToken'] = $this->nextPageToken;
        }

        return $result;
    }
}
