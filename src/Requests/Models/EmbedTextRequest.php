<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Models;

use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\Models\EmbedTextResponse;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class EmbedTextRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ApiEndpointValidator;
    use ParameterTypeValidator;

    protected Method $method = Method::POST;

    /** @var string The model name to use */
    private string $model;

    /** @var string The free-form input text that the model will turn into an embedding. */
    private string $text;

    /**
     * @param array{
     *     model: string,
     *     text: string,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);
        $this->model = $parameters['model'];
        $this->text = $parameters['text'];
        $this->validateResource(ResourceType::Model, $this->model);
    }

    protected function defaultBody(): array
    {
        return ['text' => $this->text];
    }

    public function resolveEndpoint(): string
    {
        return "/$this->model:embedText";
    }

    public function createDtoFromResponse(Response $response): EmbedTextResponse
    {
        $data = $response->json();

        return EmbedTextResponse::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'model' => ['string'],
            'text' => ['string'],
        ];
    }
}
