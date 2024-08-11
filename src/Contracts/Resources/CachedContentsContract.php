<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Contracts\Resources;

use Derrickob\GeminiApi\Data\CachedContent;
use Derrickob\GeminiApi\Responses\CachedContents\ListCachedContentResponse;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

interface CachedContentsContract
{
    /**
     *Creates CachedContent resource.
     *
     * @link https://ai.google.dev/api/caching#v1beta.cachedContents.create
     *
     * @param CachedContent $cachedContent Content that has been preprocessed and can be used in subsequent request to GenerativeService.
     *
     * @return CachedContent If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function create(CachedContent $cachedContent): CachedContent;

    /**
     * Deletes CachedContent resource.
     *
     * @link https://ai.google.dev/api/caching#v1beta.cachedContents.delete
     *
     * @param string $name The resource name referring to the content cache entry.
     *
     * @return bool If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function delete(string $name): bool;

    /**
     * Reads CachedContent resource.
     *
     * @link https://ai.google.dev/api/caching#v1beta.cachedContents.get
     *
     * @param string $name The resource name referring to the content cache entry.
     *
     * @return CachedContent If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function get(string $name): CachedContent;

    /**
     * Lists CachedContents.
     *
     * @link https://ai.google.dev/api/caching#v1beta.cachedContents.list
     *
     * @param array{
     *      pageSize?: int,
     *      pageToken?: string,
     *  } $parameters
     *
     * @return ListCachedContentResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function list(array $parameters = []): ListCachedContentResponse;

    /**
     * Updates CachedContent resource (only expiration is updatable).
     *
     * @link https://ai.google.dev/api/caching#v1beta.cachedContents.patch
     *
     * @param array{
     *     name: string,
     *     updateMask: string,
     *     cachedContent: CachedContent,
     * } $parameters
     *
     * @return CachedContent If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function patch(array $parameters): CachedContent;
}
