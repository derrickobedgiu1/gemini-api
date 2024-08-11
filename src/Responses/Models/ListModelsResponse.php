<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses\Models;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use Derrickob\GeminiApi\Data\Model;
use InvalidArgumentException;

final class ListModelsResponse implements ResponseContract
{
    /**
     * @param Model[]     $models        The returned Models.
     * @param string|null $nextPageToken A token, which can be sent as pageToken to retrieve the next page.
     */
    public function __construct(
        public array $models,
        public ?string $nextPageToken = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['models']) || !is_array($data['models'])) {
            throw new InvalidArgumentException('models must be an array');
        }

        $models = array_map(
            static function (mixed $model): Model {
                if (!is_array($model)) {
                    throw new InvalidArgumentException('Each model must be an array');
                }

                return Model::fromArray($model);
            },
            $data['models']
        );

        $nextPageToken = isset($data['nextPageToken']) && is_string($data['nextPageToken'])
            ? $data['nextPageToken']
            : null;

        return new self(
            models: $models,
            nextPageToken: $nextPageToken
        );
    }

    public function toArray(): array
    {
        $modelData = [
            'models' => array_map(
                static fn (Model $model): array => $model->toArray(),
                $this->models
            ),
        ];

        if ($this->nextPageToken !== null) {
            $modelData['nextPageToken'] = $this->nextPageToken;
        }

        return $modelData;
    }
}
