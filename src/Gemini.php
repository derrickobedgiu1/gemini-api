<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi;

use Derrickob\GeminiApi\Exceptions\ApiRequestException;
use Derrickob\GeminiApi\Requests\AuthenticateRequest;
use Derrickob\GeminiApi\Resources\CachedContents;
use Derrickob\GeminiApi\Resources\Corpora\Corpora;
use Derrickob\GeminiApi\Resources\Files;
use Derrickob\GeminiApi\Resources\Media;
use Derrickob\GeminiApi\Resources\Models;
use Derrickob\GeminiApi\Resources\TunedModels\TunedModels;
use Derrickob\GeminiApi\Traits\Validators\ParameterTypeValidator;
use Psr\Cache\CacheItemPoolInterface;
use Saloon\Http\Connector;
use Saloon\Http\Response;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Throwable;

final class Gemini extends Connector
{
    use AlwaysThrowOnErrors;
    use ParameterTypeValidator;

    private ?AuthenticateRequest $authenticateRequest;
    private readonly string $apiKey;
    private string $apiVersion;
    private string $baseUrl;
    private ?array $credentials;
    private ?array $scopes;
    private ?array $cacheConfig;
    private ?array $proxy;
    private ?CacheItemPoolInterface $cacheItemPool;

    /**
     * @param array{
     *     apiKey: string,
     *     apiVersion?: string,
     *     baseUrl?: string,
     *     credentials?: array<string, mixed>,
     *     scopes?: string[],
     *     cache?: CacheItemPoolInterface,
     *     cacheConfig?: array<string, mixed>,
     *     proxy?: array<string, mixed>,
     *     authenticateRequest?: AuthenticateRequest,
     * } $configs
     */
    public function __construct(array $configs)
    {
        $this->validateParameters($configs);
        $this->apiKey = $configs['apiKey'];
        $this->apiVersion = $configs['apiVersion'] ?? 'v1beta';
        $this->baseUrl = $configs['baseUrl'] ?? 'https://generativelanguage.googleapis.com';
        $this->credentials = $configs['credentials'] ?? null;
        $this->scopes = $configs['scopes'] ?? null;
        $this->cacheItemPool = $configs['cache'] ?? null;
        $this->cacheConfig = $configs['cacheConfig'] ?? null;
        $this->proxy = $configs['proxy'] ?? null;
        $this->authenticateRequest = $configs['authenticateRequest'] ?? null;

        $this->authenticateRequest ??= AuthenticateRequest::create(
            apiKey: $this->apiKey,
            credentials: $this->credentials,
            scopes: $this->scopes,
            cacheItemPool: $this->cacheItemPool,
            cacheConfig: $this->cacheConfig,
            proxy: $this->proxy,
        );

        $this->middleware()->onRequest($this->authenticateRequest);
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl . '/' . $this->apiVersion;
    }

    public function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    public function models(): Models
    {
        return new Models($this);
    }

    public function files(): Files
    {
        return new Files($this);
    }

    public function media(): Media
    {
        return new Media($this);
    }

    public function tunedModels(): TunedModels
    {
        return new TunedModels($this);
    }

    public function corpora(): Corpora
    {
        return new Corpora($this);
    }

    public function cachedContents(): CachedContents
    {
        return new CachedContents($this);
    }

    public function getRequestException(Response $response, ?Throwable $senderException): ?Throwable
    {
        return new ApiRequestException($response, $senderException);
    }

    protected function expectParameters(): array
    {
        return [
            'apiKey' => ['string'],
            'apiVersion' => ['string', 'null'],
            'baseUrl' => ['string', 'null'],
            'credentials' => ['array', 'null'],
            'scopes' => ['array', 'null'],
            'cache' => [CacheItemPoolInterface::class, 'null'],
            'cacheConfig' => ['array', 'null'],
            'proxy' => ['array', 'null'],
            'authenticateRequest' => [AuthenticateRequest::class, 'null'],
        ];
    }
}
