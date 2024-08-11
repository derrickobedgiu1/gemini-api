<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Bases;

use Derrickob\GeminiApi\Data\Content;
use Derrickob\GeminiApi\Data\SafetySetting;
use Derrickob\GeminiApi\Data\TextPrompt;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\GenerateTextResponse;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

abstract class TextBaseRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ParameterTypeValidator;

    protected Method $method = Method::POST;

    /** @var string The name of the Model or TunedModel to use for generating the completion. */
    protected string $model;

    /** @var TextPrompt The free-form input text given to the model as a prompt. */
    protected TextPrompt $prompt;

    /** @var SafetySetting|SafetySetting[]|null A list of unique SafetySetting instances for blocking unsafe content. */
    protected mixed $safetySettings;

    /** @var string[]|null The set of character sequences (up to 5) that will stop output generation. */
    protected ?array $stopSequences;

    /** @var float|null Controls the randomness of the output. */
    protected ?float $temperature;

    /** @var int|null The number of generated response messages to return. */
    protected ?int $candidateCount;

    /** @var int|null The maximum number of tokens to include in a candidate. */
    protected ?int $maxOutputTokens;

    /** @var float|null The maximum cumulative probability of tokens to consider when sampling. */
    protected ?float $topP;

    /** @var int|null The maximum number of tokens to consider when sampling. */
    protected ?int $topK;

    /**
     * @param array{
     *      model: string,
     *      prompt: TextPrompt,
     *      safetySettings?: SafetySetting|SafetySetting[],
     *      stopSequences?: string[],
     *      temperature?: float,
     *      candidateCount?: int,
     *      maxOutputTokens?: int,
     *      topP?: float,
     *      topK?: int,
     *  } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);
        $this->model = $parameters['model'];
        $this->prompt = $parameters['prompt'];
        $this->safetySettings = $parameters['safetySettings'] ?? null;
        $this->stopSequences = $parameters['stopSequences'] ?? null;
        $this->temperature = $parameters['temperature'] ?? null;
        $this->candidateCount = $parameters['candidateCount'] ?? null;
        $this->maxOutputTokens = $parameters['maxOutputTokens'] ?? null;
        $this->topP = $parameters['topP'] ?? null;
        $this->topK = $parameters['topK'] ?? null;
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
        $body = array_filter([
            'stopSequences' => $this->stopSequences,
            'temperature' => $this->temperature,
            'candidateCount' => $this->candidateCount,
            'maxOutputTokens' => $this->maxOutputTokens,
            'topP' => $this->topP,
            'topK' => $this->topK,
        ], fn ($value): bool => $value !== null);

        $body['prompt'] = $this->prompt->toArray();

        if ($this->safetySettings !== null) {
            $body['safetySettings'] = [SafetySetting::parseSafetySettings($this->safetySettings)];
        }

        return $body;
    }

    public function createDtoFromResponse(Response $response): GenerateTextResponse
    {
        $data = $response->json();

        return GenerateTextResponse::fromArray($data);
    }

    /**
     * {@inheritdoc}
     */
    protected function expectParameters(): array
    {
        return [
            'model' => ['string'],
            'prompt' => [TextPrompt::class],
            'safetySettings' => [SafetySetting::class, 'array','null'],
            'stopSequences' => ['array','null'],
            'temperature' => ['float','null'],
            'candidateCount' => ['int','null'],
            'maxOutputTokens' => ['int','null'],
            'topP' => ['float','null'],
            'topK' => ['int','null'],
        ];
    }
}
