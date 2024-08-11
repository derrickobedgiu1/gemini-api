<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks;

use Derrickob\GeminiApi\Data\Chunk;
use Derrickob\GeminiApi\Enums\AuthMethod;
use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Responses\Corpora\Documents\Chunks\BatchUpdateChunkResponse;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use InvalidArgumentException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class BatchUpdateChunkRequest extends Request implements HasBody
{
    use HasJsonBody;
    use ParameterTypeValidator;
    use ApiEndpointValidator;

    protected Method $method = Method::POST;
    public AuthMethod $auth = AuthMethod::ADC;

    /** @var string The name of the Document containing the Chunks to update. */
    private string $parent;

    private array $requests;

    /**
     * @param array{
     *      parent: string,
     *      requests: array{
     *          array{
     *              chunk: Chunk,
     *              updateMask: string,
     *          }
     *      }
     *  } $parameters
     */
    public function __construct(array $parameters)
    {
        $this->validateParameters($parameters);

        $this->parent = $parameters['parent'];
        $this->requests = $parameters['requests'];
        $this->validateResource(ResourceType::Document, $this->parent);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->parent/chunks:batchUpdate";
    }

    public function defaultBody(): array
    {
        return [
            'requests' => array_map(function (array $request): array {
                if (!isset($request['chunk']) || !isset($request['updateMask'])) {
                    throw new InvalidArgumentException('Invalid arguments. chunk and updateMask must be set.');
                }

                if (!$request['chunk'] instanceof Chunk) {
                    throw new InvalidArgumentException('chunk must be an instance of Chunk');
                }

                return [
                    'chunk' => $request['chunk']->toArray(),
                    'updateMask' => $request['updateMask'],
                ];
            }, $this->requests),
        ];
    }

    public function createDtoFromResponse(Response $response): BatchUpdateChunkResponse
    {
        $data = $response->json();

        return BatchUpdateChunkResponse::fromArray($data);
    }

    protected function expectParameters(): array
    {
        return [
            'parent' => ['string'],
            'requests' => ['array'],
        ];
    }
}
