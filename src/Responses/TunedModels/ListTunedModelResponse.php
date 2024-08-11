<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses\TunedModels;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use Derrickob\GeminiApi\Data\TunedModel;
use InvalidArgumentException;

final class ListTunedModelResponse implements ResponseContract
{
    /**
     * @param TunedModel[] $tunedModels   The returned Models.
     * @param string|null  $nextPageToken A token, which can be sent as `pageToken` to retrieve the next page.
     */
    public function __construct(
        public array $tunedModels,
        public ?string $nextPageToken
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['tunedModels']) || !is_array($data['tunedModels'])) {
            throw new InvalidArgumentException('tunedModels must be an array');
        }

        $tunedModels = array_map(
            static function (mixed $tunedModel): TunedModel {
                if (!is_array($tunedModel)) {
                    throw new InvalidArgumentException('Each tunedModel must be an array');
                }

                return TunedModel::fromArray($tunedModel);
            },
            $data['tunedModels']
        );

        $nextPageToken = isset($data['nextPageToken']) && is_string($data['nextPageToken'])
            ? $data['nextPageToken']
            : null;

        return new self(
            tunedModels: $tunedModels,
            nextPageToken: $nextPageToken
        );
    }

    public function toArray(): array
    {
        $data = [
            'tunedModels' => array_map(
                static fn (TunedModel $tunedModel): array => $tunedModel->toArray(),
                $this->tunedModels
            ),
        ];

        if ($this->nextPageToken !== null) {
            $data['nextPageToken'] = $this->nextPageToken;
        }

        return $data;
    }
}
