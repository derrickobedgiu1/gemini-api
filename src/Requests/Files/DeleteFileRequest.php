<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests\Files;

use Derrickob\GeminiApi\Enums\ResourceType;
use Derrickob\GeminiApi\Requests\Request;
use Derrickob\GeminiApi\Traits\Validators\ApiEndpointValidator;
use Saloon\Enums\Method;

final class DeleteFileRequest extends Request
{
    use ApiEndpointValidator;
    protected Method $method = Method::DELETE;

    /**
     * @param string $name The name of the `File` to delete.
     */
    public function __construct(private readonly string $name)
    {
        $this->validateResource(ResourceType::File, $this->name);
    }

    public function resolveEndpoint(): string
    {
        return "/$this->name";
    }
}
