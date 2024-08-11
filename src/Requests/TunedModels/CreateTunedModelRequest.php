<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\TunedModels;

use Derrickob\GeminiApi\Data\Operation;
use Derrickob\GeminiApi\Data\TunedModel;
use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use JsonException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class CreateTunedModelRequest extends Request implements HasBody
{
    use HasJsonBody;

    use ParameterTypeValidator;

    protected Method $method = Method::POST;
    public AuthMethod $auth = AuthMethod::ADC;

    /** @var TunedModel Field data for creating the tuned model. */
    private TunedModel $tunedModel;

    /** @var string|null The unique id for the tuned model if specified. */
    private ?string $tunedModelId;

    /**
     * @param array{
     *     tunedModel: TunedModel,
     *     tunedModelId?: string,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);

        $this->tunedModel = $parameters['tunedModel'];
        $this->tunedModelId = $parameters['tunedModelId'] ?? null;
    }

    public function resolveEndpoint(): string
    {
        return "/tunedModels";
    }

    public function defaultQuery(): array
    {
        return array_filter(['tunedModelId' => $this->tunedModelId]);
    }

    public function defaultBody(): array
    {
        return $this->tunedModel->toArray();
    }

    /**
     * @throws JsonException
     */
    public function createDtoFromResponse(Response $response): Operation
    {
        $data = $response->json();

        return Operation::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'tunedModel' => [TunedModel::class],
            'tunedModelId' => ['string', 'null'],
        ];
    }
}
