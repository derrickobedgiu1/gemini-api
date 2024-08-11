<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Corpora;

use Derrickob\GeminiApi\Data\Corpus;
use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Requests\Request;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class CreateCorporaRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;
    public AuthMethod $auth = AuthMethod::ADC;

    public function __construct(private readonly Corpus $corpus)
    {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/corpora";
    }

    public function defaultBody(): array
    {
        return $this->corpus->toArray();
    }

    public function createDtoFromResponse(Response $response): Corpus
    {
        $data = $response->json();

        return Corpus::fromArray($data);
    }
}
