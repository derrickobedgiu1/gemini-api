<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\TunedModels;

use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

final class TransferOwnershipRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ApiEndpointValidator;
    use ParameterTypeValidator;

    protected Method $method = Method::POST;
    public AuthMethod $auth = AuthMethod::ADC;

    /** @var string The resource name of the tuned model to transfer ownership. */
    private string $name;

    /** @var string The email address of the user to whom the tuned model is being transferred to. */
    private string $emailAddress;

    /**
     * @param array{
     *     name: string,
     *     emailAddress: string,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);
        $this->name = $parameters['name'];
        $this->emailAddress = $parameters['emailAddress'];
    }

    public function resolveEndpoint(): string
    {
        $this->validateResource(ResourceType::TunedModel, $this->name);

        return "/$this->name:transferOwnership";
    }

    protected function defaultBody(): array
    {
        return ['emailAddress' => $this->emailAddress];
    }

    protected function expectParameters(): array
    {
        return [
            'name' => ['string'],
            'emailAddress' => ['string'],
        ];
    }
}
