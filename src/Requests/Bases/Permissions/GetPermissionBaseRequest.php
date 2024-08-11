<?php

namespace Derrickob\GeminiApi\Requests\Bases\Permissions;

use Derrickob\GeminiApi\Data\Permission;
use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Requests\Request;
use Saloon\Enums\Method;
use Saloon\Http\Response;

abstract class GetPermissionBaseRequest extends Request
{
    protected Method $method = Method::GET;
    public AuthMethod $auth = AuthMethod::ADC;

    /**
     * @param string $name The resource name of the permission.
     */
    public function __construct(protected string $name)
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    abstract public function resolveEndpoint(): string;

    /**
     * {@inheritdoc}
     */
    public function createDtoFromResponse(Response $response): Permission
    {
        $data = $response->json();

        return Permission::fromArray($data);
    }
}
