<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Models;

use Derrickob\GeminiApi\Data\Content;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Enums\TaskType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\Models\BatchEmbedContentsResponse;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use InvalidArgumentException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class BatchEmbedContentsRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ApiEndpointValidator;
    use ParameterTypeValidator;

    protected Method $method = Method::POST;

    /** @var string The model's resource name. */
    private string $model;

    private array $requests;

    /**
     * @param array{
     *       model: string,
     *       requests: (string[]|array{
     *          array{
     *              content: Content|string,
     *              taskType?: TaskType,
     *              title?: string,
     *              outputDimensionality?: int,
     *          }
     *      }),
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);
        $this->model = $parameters['model'];
        $this->requests = $parameters['requests'];
        $this->validateResource(ResourceType::Model, $this->model);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->model:batchEmbedContents";
    }

    public function createDtoFromResponse(Response $response): BatchEmbedContentsResponse
    {
        $data = $response->json();

        return BatchEmbedContentsResponse::fromArray($data);
    }

    protected function defaultBody(): array
    {
        return [
            'requests' => array_map(function ($request): array {
                if (is_string($request)) {
                    return [
                        'model' => $this->model,
                        'content' => [
                            'parts' => [
                                ['text' => $request],
                            ],
                        ],
                    ];
                }
                if (is_array($request)) {
                    $embedRequest = [
                        'model' => $this->model,
                    ];
                    if (isset($request['content'])) {
                        $embedRequest['content'] = Content::parseContents($request['content']);
                    } else {
                        throw new InvalidArgumentException('Content is required for each request');
                    }
                    if (isset($request['taskType'])) {
                        $embedRequest['taskType'] = $request['taskType'] instanceof TaskType
                            ? $request['taskType']->value
                            : throw new InvalidArgumentException('taskType should be an instance of TaskType enum');
                    }
                    if (isset($request['title'])) {
                        $embedRequest['title'] = $request['title'];
                    }
                    if (isset($request['outputDimensionality'])) {
                        $embedRequest['outputDimensionality'] = $request['outputDimensionality'];
                    }

                    return $embedRequest;
                }

                throw new InvalidArgumentException('Invalid request format');
            }, $this->requests),
        ];
    }

    protected function expectParameters(): array
    {
        return [
            'model' => ['string'],
            'requests' => ['array'],
        ];
    }
}
