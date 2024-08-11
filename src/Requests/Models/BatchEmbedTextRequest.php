<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Models;

use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\Models\BatchEmbedTextResponse;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Exception;
use InvalidArgumentException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class BatchEmbedTextRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ApiEndpointValidator;
    use ParameterTypeValidator;

    protected Method $method = Method::POST;

    /** @var string The name of the Model to use for generating the embedding. */
    private string $model;

    /** @var string[]|null The free-form input texts that the model will turn into an embedding. */
    private ?array $texts;

    private ?array $requests;

    /**
     * @param array{
     *     model: string,
     *     texts?: string[],
     *     requests?: array{
     *          text: string,
     *     },
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);
        $this->model = $parameters['model'];
        $this->texts = $parameters['texts'] ?? null;
        $this->requests = $parameters['requests'] ?? null;
        $this->validateResource(ResourceType::Model, $this->model);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->model:batchEmbedText";
    }

    public function createDtoFromResponse(Response $response): BatchEmbedTextResponse
    {
        $data = $response->json();

        return BatchEmbedTextResponse::fromArray($data);
    }

    /**
     * @throws Exception
     */
    protected function defaultBody(): array
    {
        if (isset($this->texts) && isset($this->requests)) {
            throw new Exception('Only one of texts or requests should be set, not both');
        }

        if (!isset($this->texts) && !isset($this->requests)) {
            throw new Exception('Either texts or requests is required, but none was passed');
        }

        if (isset($this->texts)) {
            return [
                'requests' => array_map(fn ($text): array => [
                    'model' => $this->model,
                    'text' => $text,
                ], $this->texts),
            ];
        }

        if (isset($this->requests)) {
            return [
                'requests' => array_map(function (array $request): array {
                    if (!isset($request['text'])) {
                        throw new InvalidArgumentException('Each request must contain a "text" field');
                    }

                    return [
                        'model' => $this->model,
                        'text' => $request['text'],
                    ];
                }, $this->requests),
            ];
        }

        throw new Exception('Unexpected state: neither texts nor requests is set');
    }

    protected function expectParameters(): array
    {
        return [
            'model' => ['string'],
            'texts' => ['array', 'null'],
            'requests' => ['array', 'null'],
        ];
    }
}
