<?php

namespace Derrickob\GeminiApi\Requests\Bases\Permissions;

use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Requests\Request;
use Saloon\Enums\Method;

abstract class DeletePermissionBaseRequest extends Request
{
    protected Method $method = Method::DELETE;
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
}
