<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Tests\Resources;

use Derrickob\GeminiApi\Data\CachedContent;
use Derrickob\GeminiApi\Data\Content;
use Derrickob\GeminiApi\Requests\CachedContents\CreateCachedContentRequest;
use Derrickob\GeminiApi\Requests\CachedContents\DeleteCachedContentRequest;
use Derrickob\GeminiApi\Requests\CachedContents\GetCachedContentRequest;
use Derrickob\GeminiApi\Requests\CachedContents\ListCachedContentRequest;
use Derrickob\GeminiApi\Requests\CachedContents\PatchCachedContentRequest;
use Derrickob\GeminiApi\Responses\CachedContents\ListCachedContentResponse;
use Derrickob\GeminiApi\Tests\TestCase;

final class CachedContentsTest extends TestCase
{
    public function testCreate(): void
    {
        $this->mockResponse('cachedContents/create');
        $file = 'https://generativelanguage.googleapis.com/v1beta/files/7j0qhgcmeeqh';

        $response = $this->gemini->cachedContents()->create(
            new CachedContent(
                model: 'models/gemini-1.5-flash-001',
                displayName: 'sherlock jr movie',
                systemInstruction: Content::createTextContent("You are an expert video analyzer, and your job is to answer the user\'s query based on the video file you have access to."),
                contents: [
                    Content::createFileContent($file, 'video/mp4', 'user'),
                ],
                ttl: '3600s',
            ),
        );

        $this->mockClient->assertSent(CreateCachedContentRequest::class);
        $this->assertInstanceOf(CachedContent::class, $response);
        $this->assertIsArray($response->toArray());
    }

    public function testDelete(): void
    {
        $this->mockResponse('cachedContents/delete');

        $response = $this->gemini->cachedContents()->delete('cachedContents/4msksc6ia7b5');

        $this->mockClient->assertSent(DeleteCachedContentRequest::class);
        $this->assertTrue($response);
    }

    public function testGet(): void
    {
        $this->mockResponse('cachedContents/get');

        $response = $this->gemini->cachedContents()->get('cachedContents/iiugfwum7xxq');

        $this->mockClient->assertSent(GetCachedContentRequest::class);
        $this->assertInstanceOf(CachedContent::class, $response);
        $this->assertIsArray($response->toArray());
    }

    public function testList(): void
    {
        $this->mockResponse('cachedContents/list');

        $response = $this->gemini->cachedContents()->list();

        $this->mockClient->assertSent(ListCachedContentRequest::class);
        $this->assertInstanceOf(ListCachedContentResponse::class, $response);
        $this->assertIsArray($response->toArray());
    }

    public function testPatch(): void
    {
        $this->mockResponse('cachedContents/patch');

        $response = $this->gemini->cachedContents()->patch([
            'name' => 'cachedContents/iiugfwum7xxq',
            'updateMask' => 'ttl',
            'cachedContent' => new CachedContent(
                ttl: '7200s'
            ),
        ]);

        $this->mockClient->assertSent(PatchCachedContentRequest::class);
        $this->assertInstanceOf(CachedContent::class, $response);
        $this->assertIsArray($response->toArray());
    }
}
