<?php

namespace Derrickob\GeminiApi\Requests\Bases\Permissions;

use Derrickob\GeminiApi\Data\Permission;
use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

abstract class CreatePermissionBaseRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ParameterTypeValidator;

    protected Method $method = Method::POST;
    public AuthMethod $auth = AuthMethod::ADC;

    protected string $parent;
    protected Permission $permission;

    /**
     * @param array{
     *     parent: string,
     *     permission: Permission,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);

        $this->parent = $parameters['parent'];
        $this->permission = $parameters['permission'];
    }

    /**
     * {@inheritdoc}
     */
    abstract public function resolveEndpoint(): string;

    /**
     * {@inheritdoc}
     */
    public function defaultBody(): array
    {
        return $this->permission->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function createDtoFromResponse(Response $response): Permission
    {
        $data = $response->json();

        return Permission::fromArray($data);
    }

    /**
     * {@inheritdoc}
     */
    protected function expectParameters(): array
    {
        return [
            'parent' => ['string'],
            'permission' => [Permission::class],
        ];
    }
}
