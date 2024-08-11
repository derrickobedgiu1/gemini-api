<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Resources\Corpora;

use Derrickob\GeminiApi\Contracts\Resources\Corpora\CorporaPermissionsContract;
use Derrickob\GeminiApi\Data\Permission;
use Derrickob\GeminiApi\Requests\Corpora\Permissions\CreatePermissionRequest;
use Derrickob\GeminiApi\Requests\Corpora\Permissions\DeletePermissionRequest;
use Derrickob\GeminiApi\Requests\Corpora\Permissions\GetPermissionRequest;
use Derrickob\GeminiApi\Requests\Corpora\Permissions\ListPermissionRequest;
use Derrickob\GeminiApi\Requests\Corpora\Permissions\PatchPermissionRequest;
use Derrickob\GeminiApi\Responses\ListPermissionsResponse;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\BaseResource;

final class CorporaPermissions extends BaseResource implements CorporaPermissionsContract
{
    /**
     * Create a permission to a specific resource.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/permissions#v1beta.corpora.permissions.create
     *
     * @param array{
     *      parent: string,
     *      permission: Permission,
     *  } $parameters
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
     * @link https://ai.google.dev/api/semantic-retrieval/permissions#v1beta.corpora.permissions.delete
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
     * @link https://ai.google.dev/api/semantic-retrieval/permissions#v1beta.corpora.permissions.get
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
     * @link https://ai.google.dev/api/semantic-retrieval/permissions#v1beta.corpora.permissions.list
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
        return $this->connector->send(new ListPermissionRequest($parameters))->dtoOrFail();
    }

    /**
     * Updates the permission.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/permissions#v1beta.corpora.permissions.patch
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
