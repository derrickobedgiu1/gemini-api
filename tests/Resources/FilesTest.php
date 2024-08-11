<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Tests\Resources;

use Derrickob\GeminiApi\Data\File;
use Derrickob\GeminiApi\Requests\Files\DeleteFileRequest;
use Derrickob\GeminiApi\Requests\Files\GetFileRequest;
use Derrickob\GeminiApi\Requests\Files\ListFilesRequest;
use Derrickob\GeminiApi\Responses\Files\ListFilesResponse;
use Derrickob\GeminiApi\Tests\TestCase;

final class FilesTest extends TestCase
{
    public function testDelete(): void
    {
        $this->mockResponse('files/delete');

        $response = $this->gemini->files()->delete('files/9z58r4iu98lv');

        $this->mockClient->assertSent(DeleteFileRequest::class);
        $this->assertTrue($response);
    }

    public function testGet(): void
    {
        $this->mockResponse('files/get');

        $response = $this->gemini->files()->get('files/9z58r4iu98lv');

        $this->mockClient->assertSent(GetFileRequest::class);
        $this->assertInstanceOf(File::class, $response);
        $this->assertIsArray($response->toArray());
    }

    public function testList(): void
    {
        $this->mockResponse('files/list');

        $response = $this->gemini->files()->list();

        $this->mockClient->assertSent(ListFilesRequest::class);
        $this->assertInstanceOf(ListFilesResponse::class, $response);
        $this->assertIsArray($response->files);
        $this->assertIsArray($response->toArray());
    }
}
