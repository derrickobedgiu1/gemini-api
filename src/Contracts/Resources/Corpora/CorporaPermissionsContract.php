<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Contracts\Resources\Corpora;

use Derrickob\GeminiApi\Data\Permission;
use Derrickob\GeminiApi\Responses\ListPermissionsResponse;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

interface CorporaPermissionsContract
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
    public function create(array $parameters): Permission;

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
    public function delete(string $name): bool;

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
    public function get(string $name): Permission;

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
    public function list(array $parameters): ListPermissionsResponse;

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
    public function patch(array $parameters): Permission;
}
