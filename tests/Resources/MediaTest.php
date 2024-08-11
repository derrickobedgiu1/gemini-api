<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Tests\Resources;

use Derrickob\GeminiApi\Data\File;
use Derrickob\GeminiApi\Requests\Media\InitiateMediaUploadRequest;
use Derrickob\GeminiApi\Requests\Media\UploadMediaChunkRequest;
use Derrickob\GeminiApi\Responses\Media\UploadMediaChunkResponse;
use Derrickob\GeminiApi\Tests\TestCase;

final class MediaTest extends TestCase
{
    public function testUpload(): void
    {
        $this->mockResponse('media/initiateUpload');
        $this->mockResponse('media/uploadChunk');

        $metaData = new File(
            displayName: 'Sample File',
        );

        $filePath = __DIR__ . '/../../files/sample.png';
        $response = $this->gemini->media()->upload($filePath, $metaData);

        $this->mockClient->assertSent(InitiateMediaUploadRequest::class);
        $this->mockClient->assertSent(UploadMediaChunkRequest::class);
        $this->assertInstanceOf(UploadMediaChunkResponse::class, $response);
        $this->assertIsArray($response->toArray());
    }
}
