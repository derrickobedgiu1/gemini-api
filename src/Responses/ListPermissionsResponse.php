<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses;

use Derrickob\GeminiApi\Data\Permission;

final class ListPermissionsResponse
{
    /**
     * @param Permission[] $permissions   Returned permissions.
     * @param string|null  $nextPageToken A token, which can be sent as `pageToken` to retrieve the next page.
     */
    public function __construct(
        public array   $permissions,
        public ?string $nextPageToken = null,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        $permissions = [];

        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $permissions = array_map(
                static fn (array $permission): Permission => Permission::fromArray($permission),
                $data['permissions']
            );
        }

        return new self(
            permissions: $permissions,
            nextPageToken: $data['nextPageToken'] ?? null,
        );
    }

    public function toArray(): array
    {
        $data = [
          'permissions' => array_map(
              static fn (Permission $permission): array => $permission->toArray(),
              $this->permissions
          ),
        ];

        if($this->nextPageToken !== null) {
            $data['nextPageToken'] = $this->nextPageToken;
        }

        return $data;
    }
}
