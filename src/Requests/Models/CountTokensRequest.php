<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Models;

use Derrickob\GeminiApi\Data\Content;
use Derrickob\GeminiApi\Data\GenerationConfig;
use Derrickob\GeminiApi\Data\SafetySetting;
use Derrickob\GeminiApi\Data\Tool;
use Derrickob\GeminiApi\Data\ToolConfig;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\Models\CountTokensResponse;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use InvalidArgumentException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class CountTokensRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ApiEndpointValidator;
    use ParameterTypeValidator;

    protected Method $method = Method::POST;

    /** @var string The model's resource name. */
    private string $model;

    private mixed $contents;

    private ?array $generateContentRequest;

    /**
     * @param array{
     *     model: string,
     *     contents?: string|Content|Content[],
     *     generateContentRequest?: array{
     *         model: string,
     *         contents: string|Content|Content[],
     *         systemInstruction?: string|Content,
     *         tools?: Tool|Tool[],
     *         toolConfig?: ToolConfig,
     *         safetySettings?: SafetySetting|SafetySetting[],
     *         generationConfig?: GenerationConfig,
     *         cachedContent?: string,
     *     },
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);
        $this->model = $parameters['model'];
        $this->contents = $parameters['contents'] ?? null;
        $this->generateContentRequest = $parameters['generateContentRequest'] ?? [];
        $this->validateResource(ResourceType::Model, $this->model);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->model:countTokens";
    }

    protected function defaultBody(): array
    {
        if ($this->contents === null && !isset($this->generateContentRequest)) {
            throw new InvalidArgumentException('Missing contents and generateContentRequest parameters. At least one of them should be passed');
        }

        $body = [];

        if ($this->contents !== null) {
            $body['contents'] = [Content::parseContents($this->contents)];
        }

        if ($this->generateContentRequest !== null && $this->generateContentRequest !== []) {
            if (!isset($this->generateContentRequest['model']) || !isset($this->generateContentRequest['contents'])) {
                throw new InvalidArgumentException('model and contents parameters are required.');
            }

            $body = [
                'model' => $this->generateContentRequest['model'],
                'contents' => Content::parseContents($this->generateContentRequest['contents']),
            ];

            if (isset($this->generateContentRequest['cachedContent'])) {
                $body['cachedContent'] = $this->generateContentRequest['cachedContent'];
            }

            if (isset($this->generateContentRequest['tools'])) {
                $body['tools'] = [Tool::parseTools($this->generateContentRequest['tools'])];
            }

            if (isset($this->generateContentRequest['toolConfig'])) {
                $body['toolConfig'] = $this->generateContentRequest['toolConfig']->toArray();
            }

            if (isset($this->generateContentRequest['systemInstruction'])) {
                $body['systemInstruction'] = Content::parseContents($this->generateContentRequest['systemInstruction']);
            }

            if (isset($this->generateContentRequest['safetySettings'])) {
                $body['safetySettings'] = [SafetySetting::parseSafetySettings($this->generateContentRequest['safetySettings'])];
            }

            $body['generateContentRequest'] = $body;
        }

        return $body;
    }

    public function createDtoFromResponse(Response $response): CountTokensResponse
    {
        $data = $response->json();

        return CountTokensResponse::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'model' => ['string'],
            'contents' => ['array', 'string', 'null'],
            'generateContentRequest' => ['array', 'string', 'null'],
        ];
    }
}
