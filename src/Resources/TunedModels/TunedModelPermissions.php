<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Resources\TunedModels;

use Derrickob\GeminiApi\Contracts\Resources\TunedModels\TunedModelPermissionsContract;
use Derrickob\GeminiApi\Data\Permission;
use Derrickob\GeminiApi\Requests\TunedModels\Permissions\CreatePermissionRequest;
use Derrickob\GeminiApi\Requests\TunedModels\Permissions\DeletePermissionRequest;
use Derrickob\GeminiApi\Requests\TunedModels\Permissions\GetPermissionRequest;
use Derrickob\GeminiApi\Requests\TunedModels\Permissions\ListPermissionsRequest;
use Derrickob\GeminiApi\Requests\TunedModels\Permissions\PatchPermissionRequest;
use Derrickob\GeminiApi\Responses\ListPermissionsResponse;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\BaseResource;

final class TunedModelPermissions extends BaseResource implements TunedModelPermissionsContract
{
    /**
     * Create a permission to a specific resource.
     *
     * @link https://ai.google.dev/api/tuning/permissions#v1beta.tunedModels.permissions.create
     *
     * @param array{
     *      parent: string,
     *      permission: Permission,
     * } $parameters
     *
     * @return Permission If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function create(array $parameters): Permission
    {
        /** @var Permission */
        return $this->connector->send(new CreatePermissionRequest($parameters))->dtoOrFail();
    }

    /**
     * Deletes the permission.
     *
     * @link https://ai.google.dev/api/tuning/permissions#v1beta.tunedModels.permissions.delete
     *
     * @param string $name The resource name of the permission.
     *
     * @return bool If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function delete(string $name): bool
    {
        /** @var bool */
        return $this->connector->send(new DeletePermissionRequest($name))->successful();
    }

    /**
     * Gets information about a specific Permission.
     *
     * @link https://ai.google.dev/api/tuning/permissions#v1beta.tunedModels.permissions.get
     *
     * @param string $name The resource name of the permission.
     *
     * @return Permission If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function get(string $name): Permission
    {
        /** @var Permission */
        return $this->connector->send(new GetPermissionRequest($name))->dtoOrFail();
    }

    /**
     * Lists permissions for the specific resource.
     *
     * @link https://ai.google.dev/api/tuning/permissions#v1beta.tunedModels.permissions.list
     *
     * @param array{
     *     parent: string,
     *     pageSize?: int,
     *     pageToken?: string,
     * } $parameters
     *
     * @return ListPermissionsResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function list(array $parameters): ListPermissionsResponse
    {
        /** @var ListPermissionsResponse */
        return $this->connector->send(new ListPermissionsRequest($parameters))->dtoOrFail();
    }

    /**
     * Updates the permission.
     *
     * @link https://ai.google.dev/api/tuning/permissions#v1beta.tunedModels.permissions.patch
     *
     * @param array{
     *      name: string,
     *      updateMask: string,
     *      permission: Permission,
     *  } $parameters
     *
     * @return Permission If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function patch(array $parameters): Permission
    {
        /** @var Permission */
        return $this->connector->send(new PatchPermissionRequest($parameters))->dtoOrFail();
    }
}
