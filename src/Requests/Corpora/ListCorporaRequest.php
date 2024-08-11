<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Corpora;

use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\Corpora\ListCorporaResponse;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Saloon\Enums\Method;
use Saloon\Http\Response;

final class ListCorporaRequest extends Request
{
    use ParameterTypeValidator;

    protected Method $method = Method::GET;
    public AuthMethod $auth = AuthMethod::ADC;

    /** @var int|null The maximum number of Corpora to return (per page). */
    private ?int $pageSize;

    /** @var string|null A page token, received from a previous corpora.list call. */
    private ?string $pageToken;

    /**
     * @param array{
     *     pageSize?: int,
     *     pageToken?: string,
     * } $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->validateParameters($parameters);

        $this->pageSize = $parameters['pageSize'] ?? null;
        $this->pageToken = $parameters['pageToken'] ?? null;
    }

    public function resolveEndpoint(): string
    {
        return "/corpora";
    }

    public function defaultQuery(): array
    {
        return array_filter(['pageSize' => $this->pageSize, 'pageToken' => $this->pageToken]);
    }

    public function createDtoFromResponse(Response $response): ListCorporaResponse
    {
        $data = $response->json();

        return ListCorporaResponse::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'pageSize' => ['int', 'null'],
            'pageToken' => ['string', 'null'],
        ];
    }
}
