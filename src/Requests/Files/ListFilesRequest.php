<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Files;

use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\Files\ListFilesResponse;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Enums\Method;
use Saloon\Http\Response;

final class ListFilesRequest extends Request
{
    use ParameterTypeValidator;

    protected Method $method = Method::GET;

    /** @var int|null Maximum number of Files to return per page. */
    private ?int $pageSize;

    /** @var string|null A page token from a previous files.list call. */
    private ?string $pageToken;

    /**
     * @param array{
     *     pageSize?: int,
     *     pageToken?: string,
     * } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);

        $this->pageSize = $parameters['pageSize'] ?? null;
        $this->pageToken = $parameters['pageToken'] ?? null;
    }

    public function resolveEndpoint(): string
    {
        return "/files";
    }

    protected function defaultQuery(): array
    {
        return array_filter(['pageSize' => $this->pageSize, 'pageToken' => $this->pageToken]);
    }

    public function createDtoFromResponse(Response $response): ListFilesResponse
    {
        $data = $response->json();

        return ListFilesResponse::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'pageSize' => ['int', 'null'],
            'pageToken' => ['string', 'null'],
        ];
    }
}
