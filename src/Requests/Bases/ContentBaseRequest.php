<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Bases;

use Derrickob\GeminiApi\Data\Content;
use Derrickob\GeminiApi\Data\GenerationConfig;
use Derrickob\GeminiApi\Data\SafetySetting;
use Derrickob\GeminiApi\Data\Tool;
use Derrickob\GeminiApi\Data\ToolConfig;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\GenerateContentResponse;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Plugins\HasTimeout;

abstract class ContentBaseRequest extends Request implements HasBody
{
    use HasTimeout;
    use HasJsonBody;
    use ParameterTypeValidator;

    protected Method $method = Method::POST;

    /** @var string The name of the Model to use for generating the completion. */
    protected string $model;

    /** @var Content|Content[]|string The content of the current conversation with the model. */
    protected mixed $contents;

    /** @var Tool|Tool[]|null A list of Tools the Model may use to generate the next response. */
    protected mixed $tools;

    /** @var ToolConfig|null Tool configuration for any Tool specified in the request. */
    protected ?ToolConfig $toolConfig;

    /** @var SafetySetting|SafetySetting[]|null A list of unique SafetySetting instances for blocking unsafe content. */
    protected mixed $safetySettings;

    /** @var Content|string|null Developer set system instruction(s). */
    protected mixed $systemInstruction;

    /** @var GenerationConfig|null Configuration options for model generation and outputs. */
    protected ?GenerationConfig $generationConfig;

    /** @var string|null The name of the content cached to use as context to serve the prediction. */
    protected ?string $cachedContent;

    protected int $connectTimeout = 60;
    protected int $requestTimeout = 120;

    /**
     * @param array{
     *     model: string,
     *     contents: string|Content|Content[],
     *     tools?: Tool|Tool[],
     *     toolConfig?: ToolConfig,
     *     safetySettings?: SafetySetting|SafetySetting[],
     *     systemInstruction?: string|Content,
     *     generationConfig?: GenerationConfig,
     *     cachedContent?: string,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);
        $this->model = $parameters['model'];
        $this->contents = $parameters['contents'];
        $this->tools = $parameters['tools'] ?? null;
        $this->toolConfig = $parameters['toolConfig'] ?? null;
        $this->safetySettings = $parameters['safetySettings'] ?? null;
        $this->systemInstruction = $parameters['systemInstruction'] ?? null;
        $this->generationConfig = $parameters['generationConfig'] ?? null;
        $this->cachedContent = $parameters['cachedContent'] ?? null;
    }

    abstract public function resolveEndpoint(): string;

    public function defaultBody(): array
    {
        $body = [
            'contents' => [Content::parseContents($this->contents)],
        ];

        if ($this->tools instanceof Tool) {
            $body['tools'] = [Tool::parseTools($this->tools)];
        }

        if ($this->toolConfig instanceof ToolConfig) {
            $body['toolConfig'] = $this->toolConfig->toArray();
        }

        if ($this->systemInstruction !== null) {
            $body['systemInstruction'] = Content::parseContents($this->systemInstruction);
        }

        if ($this->safetySettings !== null) {
            $body['safetySettings'] = [SafetySetting::parseSafetySettings($this->safetySettings)];
        }

        if ($this->generationConfig instanceof GenerationConfig) {
            $body['generationConfig'] = $this->generationConfig->toArray();
        }

        if ($this->cachedContent !== null) {
            $body['cachedContent'] = $this->cachedContent;
        }

        return $body;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        $data = $response->json();

        return GenerateContentResponse::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'model' => ['string'],
            'contents' => [Content::class, 'string', 'array'],
            'tools' => [Tool::class, 'array', 'null'],
            'toolConfig' => [ToolConfig::class, 'null'],
            'safetySettings' => [SafetySetting::class, 'array', 'null'],
            'systemInstruction' => [Content::class, 'string', 'null'],
            'generationConfig' => [GenerationConfig::class, 'null'],
            'cachedContent' => ['string', 'null'],
        ];
    }
}
