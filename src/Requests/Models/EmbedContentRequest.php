<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Models;

use Derrickob\GeminiApi\Data\Content;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Enums\TaskType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\Models\EmbedContentResponse;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class EmbedContentRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ApiEndpointValidator;
    use ParameterTypeValidator;

    protected Method $method = Method::POST;

    /** @var string The model's resource name. */
    private string $model;

    /** @var Content|string The content to embed. Only the parts.text fields will be counted. */
    private mixed $content;

    /** @var TaskType|null Optional task type for which the embeddings will be used. */
    private ?TaskType $taskType;

    /** @var string|null An optional title for the text. Only applicable when TaskType is RETRIEVAL_DOCUMENT. */
    private ?string $title;

    /** @var int|null Optional reduced dimension for the output embedding. */
    private ?int $outputDimensionality;

    /**
     * @param array{
     *     model: string,
     *     content: Content|string,
     *     taskType?: TaskType,
     *     title?: string,
     *     outputDimensionality?: int,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);
        $this->model = $parameters['model'];
        $this->content = $parameters['content'];
        $this->taskType = $parameters['taskType'] ?? null;
        $this->title = $parameters['title'] ?? null;
        $this->outputDimensionality = $parameters['outputDimensionality'] ?? null;
        $this->validateResource(ResourceType::Model, $this->model);
    }

    protected function defaultBody(): array
    {
        $body = [
            'content' => Content::parseContents($this->content),
        ];

        if ($this->taskType instanceof TaskType) {
            $body['taskType'] = $this->taskType->value;
        }

        if ($this->title !== null) {
            $body['title'] = $this->title;
        }

        if ($this->outputDimensionality !== null) {
            $body['outputDimensionality'] = $this->outputDimensionality;
        }

        return $body;
    }

    public function resolveEndpoint(): string
    {
        return "/$this->model:embedContent";
    }

    public function createDtoFromResponse(Response $response): EmbedContentResponse
    {
        $data = $response->json();

        return EmbedContentResponse::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'model' => ['string'],
            'content' => [Content::class, 'string'],
            'taskType' => [TaskType::class, 'null'],
            'title' => ['string', 'null'],
            'outputDimensionality' => ['int', 'null'],
        ];
    }
}
