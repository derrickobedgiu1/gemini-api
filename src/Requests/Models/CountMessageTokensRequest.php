<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Models;

use Derrickob\GeminiApi\Data\MessagePrompt;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\Models\CountMessageTokensResponse;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class CountMessageTokensRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ApiEndpointValidator;
    use ParameterTypeValidator;

    protected Method $method = Method::POST;

    /** @var string The model's resource name. */
    private string $model;

    /** @var MessagePrompt The prompt, whose token count is to be returned. */
    private MessagePrompt $prompt;

    /**
     * @param array{
     *     model: string,
     *     prompt: MessagePrompt,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);
        $this->model = $parameters['model'];
        $this->prompt = $parameters['prompt'];
        $this->validateResource(ResourceType::Model, $this->model);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->model:countMessageTokens";
    }

    protected function defaultBody(): array
    {
        return [
          'prompt' => $this->prompt->toArray(),
        ];
    }

    public function createDtoFromResponse(Response $response): CountMessageTokensResponse
    {
        $data = $response->json();

        return CountMessageTokensResponse::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'model' => ['string'],
            'prompt' => [MessagePrompt::class],
        ];
    }
}
