<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\TunedModels;

use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\TunedModels\ListTunedModelResponse;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Enums\Method;
use Saloon\Http\Response;

final class ListTunedModelRequest extends Request
{
    use ParameterTypeValidator;

    protected Method $method = Method::GET;
    public AuthMethod $auth = AuthMethod::ADC;

    /** @var int|null The maximum number of TunedModels to return (per page). */
    private ?int $pageSize;

    /** @var string|null A page token, received from a previous tunedModels.list call. */
    private ?string $pageToken;

    /** @var string|null A filter is a full text search over the tuned model's description and display name. By default, results will not include tuned models shared with everyone. */
    private ?string $filter;

    /**
     * @param array{
     *     pageSize?: int,
     *     pageToken?: string,
     *     filter?: string,
     * } $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->validateParameters($parameters);

        $this->pageSize = $parameters['pageSize'] ?? null;
        $this->pageToken = $parameters['pageToken'] ?? null;
        $this->filter = $parameters['filter'] ?? null;
    }

    public function resolveEndpoint(): string
    {
        return "/tunedModels";
    }

    public function defaultQuery(): array
    {
        return array_filter(['pageSize' => $this->pageSize, 'pageToken' => $this->pageToken, 'filter' => $this->filter]);
    }

    public function createDtoFromResponse(Response $response): ListTunedModelResponse
    {
        $data = $response->json();

        return ListTunedModelResponse::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'pageSize' => ['int', 'null'],
            'pageToken' => ['string', 'null'],
            'filter' => ['string', 'null'],
        ];
    }
}
