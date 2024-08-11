<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses\Files;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use Derrickob\GeminiApi\Data\File;

final class ListFilesResponse implements ResponseContract
{
    /**
     * @param File[]      $files         An array of Corpus objects.
     * @param string|null $nextPageToken Optional token for retrieving the next page of results.
     */
    public function __construct(
        public array   $files,
        public ?string $nextPageToken = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $files = [];
        $nextPageToken = null;

        if (isset($data['files']) && is_array($data['files'])) {
            $files = array_map(
                static fn (array $file): File => File::fromArray($file),
                $data['files']
            );
        }

        if (isset($data['nextPageToken']) && is_string($data['nextPageToken'])) {
            $nextPageToken = $data['nextPageToken'];
        }

        return new self(
            files: $files,
            nextPageToken: $nextPageToken
        );
    }

    public function toArray(): array
    {
        $result = [
            'files' => array_map(
                static fn (File $file): array => $file->toArray(),
                $this->files
            ),
        ];

        if ($this->nextPageToken !== null) {
            $result['nextPageToken'] = $this->nextPageToken;
        }

        return $result;
    }
}
