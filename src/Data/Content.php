<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use Derrickob\GeminiApi\Enums\Role;
use Derrickob\GeminiApi\Traits\Data\HasContentData;
use InvalidArgumentException;

final class Content
{
    use HasContentData;

    /**
     * @param Part[]    $parts Ordered Parts that constitute a single message.
     * @param Role|null $role  The producer of the content.
     */
    public function __construct(
        public array $parts,
        public ?Role $role = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['parts'])) {
            throw new InvalidArgumentException("parts is required but not passed in array");
        }

        $parts = array_map(
            static fn (array $part): Part => Part::fromArray($part),
            $data['parts'],
        );

        return new self(
            parts: $parts,
            role: isset($data['role']) ? Role::from($data['role']) : Role::User
        );
    }

    public function toArray(): array
    {
        $result = [
            'parts' => array_map(
                static fn (Part $part): array => $part->toArray(),
                $this->parts,
            ),
        ];

        if ($this->role instanceof Role) {
            $result['role'] = $this->role->value;
        }

        return $result;
    }
}
