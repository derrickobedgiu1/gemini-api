<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\TunedModels;

use Derrickob\GeminiApi\Data\TunedModel;
use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class PatchTunedModelRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ApiEndpointValidator;
    use ParameterTypeValidator;

    protected Method $method = Method::PATCH;
    public AuthMethod $auth = AuthMethod::ADC;

    /** @var string The tuned model name. */
    private string $name;

    /** @var string The list of fields to update. */
    private string $updateMask;

    private TunedModel $tunedModel;

    /**
     * @param array{
     *     name: string,
     *     updateMask: string,
     *     tunedModel: TunedModel,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);

        $this->name = $parameters['name'];
        $this->updateMask = $parameters['updateMask'];
        $this->tunedModel = $parameters['tunedModel'];
        $this->validateResource(ResourceType::TunedModel, $this->name);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->name";
    }

    protected function defaultQuery(): array
    {
        return ['updateMask' => $this->updateMask];
    }

    protected function defaultBody(): array
    {
        $body = $this->tunedModel->toArray();
        unset($body['name']);

        return $body;
    }

    public function createDtoFromResponse(Response $response): TunedModel
    {
        $data = $response->json();

        return TunedModel::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'name' => ['string'],
            'updateMask' => ['string'],
            'tunedModel' => [TunedModel::class],
        ];
    }
}
