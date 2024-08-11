<?php

namespace Derrickob\GeminiApi\Responses\TunedModels\Operations;

use Derrickob\GeminiApi\Data\Operation;

final class ListOperationsResponse
{
    /**
     * @param Operation[] $operations    A list of operations that matches the specified filter in the request.
     * @param string|null $nextPageToken The standard List next-page token.
     */
    public function __construct(
        public array   $operations,
        public ?string $nextPageToken,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $operations = array_map(
            static fn (array $operation): Operation => Operation::fromArray($operation),
            $data['operations'] ?? [],
        );
        $nextPageToken = $data['nextPageToken'] ?? null;

        return new self(
            operations: $operations,
            nextPageToken: $nextPageToken
        );
    }

    public function toArray(): array
    {
        $result = [
            'operations' => array_map(
                static fn (Operation $operation): array => $operation->toArray(),
                $this->operations
            ),
        ];

        if($this->nextPageToken !== null) {
            $result['nextPageToken'] = $this->nextPageToken;
        }

        return $result;
    }
}
