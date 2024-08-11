<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

final class Operation
{
    /**
     * @param string      $name     The server-assigned name, which is only unique within the same service that originally returns it.
     * @param array|null  $metadata Service-specific metadata associated with the operation.
     * @param bool|null   $done     If the value is false, it means the operation is still in progress. If true, the operation is completed, and either error or response is available.
     * @param Status|null $error    The error result of the operation in case of failure or cancellation.
     * @param array|null  $response The normal, successful response of the operation.
     */
    public function __construct(
        public string $name,
        public ?array $metadata = null,
        public ?bool $done = false,
        public ?Status $error = null,
        public ?array $response = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            metadata: $data['metadata'] ?? null,
            done: $data['done'] ?? false,
            error: isset($data['error']) ? Status::fromArray($data['error']) : null,
            response: $data['response'] ?? null,
        );
    }

    public function toArray(): array
    {
        $result = [
            'name' => $this->name,
            'done' => $this->done,
        ];

        if ($this->metadata !== null) {
            $result['metadata'] = $this->metadata;
        }

        if ($this->error instanceof Status) {
            $result['error'] = $this->error->toArray();
        }

        if ($this->response !== null) {
            $result['response'] = $this->response;
        }

        return $result;
    }
}
