<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Models;

use Derrickob\GeminiApi\Data\Content;
use Derrickob\GeminiApi\Data\GroundingPassages;
use Derrickob\GeminiApi\Data\SafetySetting;
use Derrickob\GeminiApi\Data\SemanticRetrieverConfig;
use Derrickob\GeminiApi\Enums\AnswerStyle;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\Models\GenerateAnswerResponse;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class GenerateAnswerRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ApiEndpointValidator;
    use ParameterTypeValidator;

    protected Method $method = Method::POST;

    /** @var string The name of the Model to use for generating the grounded response. */
    private string $model;

    /** @var Content[] The content of the current conversation with the Model. */
    private array $contents;

    /** @var AnswerStyle Style in which answers should be returned. */
    private AnswerStyle $answerStyle;

    /** @var SafetySetting|SafetySetting[]|null A list of unique SafetySetting instances for blocking unsafe content. */
    private mixed $safetySettings;

    /** @var GroundingPassages|null Passages provided inline with the request. */
    private ?GroundingPassages $inlinePassages;

    /** @var SemanticRetrieverConfig|null Controls the randomness of the output. */
    private ?SemanticRetrieverConfig $semanticRetriever;

    /** @var float|null Controls the randomness of the output. */
    private ?float $temperature;

    /**
     * @param array{
     *     model: string,
     *     contents: Content[],
     *     answerStyle: AnswerStyle,
     *     safetySettings?: SafetySetting|SafetySetting[],
     *     inlinePassages?: GroundingPassages,
     *     semanticRetriever?: SemanticRetrieverConfig,
     *     temperature?: float,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);
        $this->model = $parameters['model'];
        $this->contents = $parameters['contents'];
        $this->answerStyle = $parameters['answerStyle'];
        $this->safetySettings = $parameters['safetySettings'] ?? null;
        $this->inlinePassages = $parameters['inlinePassages'] ?? null;
        $this->semanticRetriever = $parameters['semanticRetriever'] ?? null;
        $this->temperature = $parameters['temperature'] ?? null;
        $this->validateResource(ResourceType::Model, $this->model);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->model:generateAnswer";
    }

    protected function defaultBody(): array
    {
        $body = [
            'contents' => [Content::parseContents($this->contents)],
            'answerStyle' => $this->answerStyle->value,
        ];

        if ($this->safetySettings !== null) {
            $body['safetySettings'] = [SafetySetting::parseSafetySettings($this->safetySettings)];
        }

        if ($this->inlinePassages instanceof GroundingPassages) {
            $body['inlinePassages'] = $this->inlinePassages->toArray();
        }

        if ($this->semanticRetriever instanceof SemanticRetrieverConfig) {
            $body['semanticRetriever'] = $this->semanticRetriever->toArray();
        }

        if ($this->temperature !== null) {
            $body['temperature'] = $this->temperature;
        }

        return $body;
    }

    public function createDtoFromResponse(Response $response): GenerateAnswerResponse
    {
        $data = $response->json();

        return GenerateAnswerResponse::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'model' => ['string'],
            'contents' => ['array'],
            'answerStyle' => [AnswerStyle::class],
            'safetySettings' => [SafetySetting::class, 'array', 'null'],
            'inlinePassages' => [GroundingPassages::class, 'null'],
            'semanticRetriever' => [SemanticRetrieverConfig::class, 'null'],
            'temperature' => ['float', 'null'],
        ];
    }
}
