<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Data;

use Derrickob\GeminiApi\Enums\GranteeType;
use Derrickob\GeminiApi\Enums\PermissionRole;

final class Permission
{
    /**
     * @param PermissionRole   $role         The role granted by this permission.
     * @param string|null      $name         The permission name. A unique name will be generated on create
     * @param GranteeType|null $granteeType  The type of the grantee.
     * @param string|null      $emailAddress The email address of the user of group which this permission refers.
     */
    public function __construct(
        public PermissionRole $role,
        public ?string        $name = null,
        public ?GranteeType   $granteeType = null,
        public ?string        $emailAddress = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            role: PermissionRole::from($data['role']),
            name: $data['name'] ?? null,
            granteeType: isset($data['granteeType']) ? GranteeType::from($data['granteeType']) : null,
            emailAddress: $data['emailAddress'] ?? null,
        );
    }

    public function toArray(): array
    {
        $result = array_filter([
            'role' => $this->role->value,
            'name' => $this->name,
            'emailAddress' => $this->emailAddress,
        ], fn ($value): bool => $value !== null);

        if ($this->granteeType instanceof GranteeType) {
            $result['granteeType'] = $this->granteeType->value;
        }

        return $result;
    }
}
