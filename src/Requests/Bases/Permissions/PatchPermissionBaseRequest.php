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

abstract class PatchPermissionBaseRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ParameterTypeValidator;

    protected Method $method = Method::PATCH;
    public AuthMethod $auth = AuthMethod::ADC;
    protected string $name;
    protected string $updateMask;
    protected Permission $permission;

    /**
     * @param array{
     *     name: string,
     *     updateMask: string,
     *     permission: Permission,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);

        $this->name = $parameters['name'];
        $this->updateMask = $parameters['updateMask'];
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
    protected function defaultQuery(): array
    {
        return ['updateMask' => $this->updateMask];
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
            'name' => ['string'],
            'updateMask' => ['string'],
            'permission' => [Permission::class],
        ];
    }
}
