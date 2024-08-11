<?php

namespace Derrickob\GeminiApi\Requests\Models;

use Derrickob\GeminiApi\Data\MessagePrompt;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\Models\GenerateMessageResponse;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class GenerateMessageRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ApiEndpointValidator;
    use ParameterTypeValidator;

    protected Method $method = Method::POST;

    /** @var string The name of the model to use. */
    private string $model;

    /** @var MessagePrompt The structured textual input given to the model as a prompt. */
    private MessagePrompt $prompt;

    /** @var float|null Controls the randomness of the output. */
    private ?float $temperature;

    /** @var int|null The number of generated response messages to return. */
    private ?int $candidateCount;

    /** @var float|null The maximum cumulative probability of tokens to consider when sampling. */
    private ?float $topP;

    /** @var int|null The maximum number of tokens to consider when sampling. */
    private ?int $topK;

    /**
     * @param array{
     *     model: string,
     *     prompt: MessagePrompt,
     *     temperature?: float,
     *     candidateCount?: int,
     *     topP?: float,
     *     topK?: int,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);
        $this->model = $parameters['model'];
        $this->prompt = $parameters['prompt'];
        $this->temperature = $parameters['temperature'] ?? null;
        $this->candidateCount = $parameters['candidateCount'] ?? null;
        $this->topP = $parameters['topP'] ?? null;
        $this->topK = $parameters['topK'] ?? null;
        $this->validateResource(ResourceType::Model, $this->model);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->model:generateMessage";
    }

    public function defaultBody(): array
    {
        $body = array_filter([
            'temperature' => $this->temperature,
            'candidateCount' => $this->candidateCount,
            'topP' => $this->topP,
            'topK' => $this->topK,
        ], fn ($value): bool => $value !== null);

        $body['prompt'] = $this->prompt->toArray();

        return $body;
    }

    public function createDtoFromResponse(Response $response): GenerateMessageResponse
    {
        $data = $response->json();

        return GenerateMessageResponse::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'model' => ['string'],
            'prompt' => [MessagePrompt::class],
            'temperature' => ['float','null'],
            'candidateCount' => ['int','null'],
            'topP' => ['float','null'],
            'topK' => ['int','null'],
        ];
    }
}
