<?php

namespace Derrickob\GeminiApi\Contracts;

use Psr\Cache\CacheItemPoolInterface;
use Saloon\Http\PendingRequest;

interface AuthenticateContract
{
    public function setupADC(PendingRequest $pendingRequest): void;

    public function setupApiKey(PendingRequest $pendingRequest): void;

    /**
     * @param string                      $apiKey        Google Gemini API Key.
     * @param array<string, mixed>|null   $credentials   Manual credentials to use for authentication.
     * @param string[]|null               $scopes        Scopes to use for service account.
     * @param CacheItemPoolInterface|null $cacheItemPool PSR-6 interface for caching service account tokens.
     * @param array<string, mixed>|null   $cacheConfig   Cache configuration for service account authentication.
     * @param array<string, mixed>|null   $proxy         Proxy configuration for requests.
     */
    public static function create(string $apiKey, ?array $credentials = null, ?array $scopes = null, ?CacheItemPoolInterface $cacheItemPool = null, ?array $cacheConfig = null, ?array $proxy = null): self;

    public function __invoke(PendingRequest $pendingRequest): void;

    /**
     * @return array<string>
     */
    public function defaultScopes(): array;
}
