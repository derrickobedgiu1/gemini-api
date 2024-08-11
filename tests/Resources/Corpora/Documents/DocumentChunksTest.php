<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Tests\Resources\Corpora\Documents;

use Derrickob\GeminiApi\Data\Chunk;
use Derrickob\GeminiApi\Data\ChunkData;
use Derrickob\GeminiApi\Data\CustomMetadata;
use Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks\BatchCreateChunksRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks\BatchDeleteChunksRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks\BatchUpdateChunkRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks\CreateChunkRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks\DeleteChunkRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks\GetChunkRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks\ListChunkRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\Chunks\PatchChunkRequest;
use Derrickob\GeminiApi\Responses\Corpora\Documents\Chunks\BatchCreateChunksResponse;
use Derrickob\GeminiApi\Responses\Corpora\Documents\Chunks\BatchUpdateChunkResponse;
use Derrickob\GeminiApi\Responses\Corpora\Documents\Chunks\ListChunkResponse;
use Derrickob\GeminiApi\Tests\TestCase;

final class DocumentChunksTest extends TestCase
{
    public function testBatchCreate(): void
    {
        $this->mockResponse('corpora/documents/chunks/batchCreate');

        $chunks = [
            [
                'chunk' => new Chunk(
                    data: new ChunkData("chunk text 1"),
                    customMetadata: [
                        new CustomMetadata(
                            key: 'key-1',
                            stringValue: 'some saloon value',
                        ),
                    ],
                ),
            ],
            [
                'chunk' => new Chunk(
                    data: new ChunkData("also some saloon chunk text"),
                    customMetadata: [
                        new CustomMetadata(
                            key: 'key-2',
                            stringValue: 'some more value here',
                        ),
                    ],
                ),
            ],
        ];

        $response = $this->gemini->corpora()->documents()->chunks()->batchCreate([
            'parent' => 'corpora/test-corpus-j0oywm69m798/documents/test-document-rl76h09upqj3',
            'requests' => $chunks,
        ]);

        $this->mockClient->assertSent("*/chunks:batchCreate");
        $this->mockClient->assertSent(BatchCreateChunksRequest::class);
        $this->assertInstanceOf(BatchCreateChunksResponse::class, $response);
    }

    public function testBatchDelete(): void
    {
        $this->mockResponse('corpora/documents/chunks/batchDelete');

        $response = $this->gemini->corpora()->documents()->chunks()->batchDelete([
            'parent' => 'corpora/test-corpus-j0oywm69m798/documents/test-document-rl76h09upqj3',
            'requests' => [
                'corpora/test-corpus-j0oywm69m798/documents/test-document-rl76h09upqj3/chunks/f9r5tryc1zz9',
                'corpora/test-corpus-j0oywm69m798/documents/test-document-rl76h09upqj3/chunks/irklkw3iwnzs',
            ],
        ]);

        $this->mockClient->assertSent("*/chunks:batchDelete");
        $this->mockClient->assertSent(BatchDeleteChunksRequest::class);
        $this->assertTrue($response);
    }

    public function testBatchUpdate(): void
    {
        $this->mockResponse('corpora/documents/chunks/batchUpdate');

        $chunks = [
            [
                'chunk' => new Chunk(
                    data: new ChunkData('latest chunk text 1'),
                    name: 'corpora/test-corpus-j0oywm69m798/documents/test-document-rl76h09upqj3/chunks/f9r5tryc1zz9',
                ),
                'updateMask' => 'data',
            ],
            [
                'chunk' => new Chunk(
                    data: new ChunkData('new saloon chunk text'),
                    name: 'corpora/test-corpus-j0oywm69m798/documents/test-document-rl76h09upqj3/chunks/irklkw3iwnzs',
                ),
                'updateMask' => 'data',
            ],
        ];

        $response = $this->gemini->corpora()->documents()->chunks()->batchUpdate([
            'parent' => 'corpora/test-corpus-j0oywm69m798/documents/test-document-rl76h09upqj3',
            'requests' => $chunks,
        ]);

        $this->mockClient->assertSent("*/chunks:batchUpdate");
        $this->mockClient->assertSent(BatchUpdateChunkRequest::class);
        $this->assertInstanceOf(BatchUpdateChunkResponse::class, $response);
    }

    public function testCreate(): void
    {
        $this->mockResponse('corpora/documents/chunks/create');

        $response = $this->gemini->corpora()->documents()->chunks()->create([
            'parent' => 'corpora/test-corpus-j0oywm69m798/documents/test-document-rl76h09upqj3',
            'chunk' => new Chunk(
                data: new ChunkData("some saloon text"),
                customMetadata: [
                    new CustomMetadata(
                        key: 'example-key',
                        stringValue: 'some value from saloon',
                    ),
                ],
            ),
        ]);

        $this->mockClient->assertSent(CreateChunkRequest::class);
        $this->assertInstanceOf(Chunk::class, $response);
    }

    public function testDelete(): void
    {
        $this->mockResponse('corpora/documents/chunks/delete');

        $response = $this->gemini->corpora()->documents()->chunks()->delete('corpora/test-corpus-j0oywm69m798/documents/test-document-rl76h09upqj3/chunks/ohaeaf6bdz5e');

        $this->mockClient->assertSent(DeleteChunkRequest::class);
        $this->assertTrue($response);
    }

    public function testGet(): void
    {
        $this->mockResponse('corpora/documents/chunks/get');

        $chunk = 'corpora/test-corpus-j0oywm69m798/documents/test-document-rl76h09upqj3/chunks/dpdyqraar6lk';
        $response = $this->gemini->corpora()->documents()->chunks()->get($chunk);

        $this->mockClient->assertSent(GetChunkRequest::class);
        $this->assertInstanceOf(Chunk::class, $response);
    }

    public function testList(): void
    {
        $this->mockResponse('corpora/documents/chunks/list');

        $response = $this->gemini->corpora()->documents()->chunks()->list([
            'parent' => 'corpora/test-corpus-j0oywm69m798/documents/test-document-rl76h09upqj3',
        ]);

        $this->mockClient->assertSent(ListChunkRequest::class);
        $this->assertInstanceOf(ListChunkResponse::class, $response);
    }

    public function testPatch(): void
    {
        $this->mockResponse('corpora/documents/chunks/patch');

        $response = $this->gemini->corpora()->documents()->chunks()->patch([
            'name' => 'corpora/test-corpus-j0oywm69m798/documents/test-document-rl76h09upqj3/chunks/dpdyqraar6lk',
            'updateMask' => 'data',
            'chunk' => new Chunk(
                data: new ChunkData('saloon new sample chunk text'),
            ),
        ]);

        $this->mockClient->assertSent(PatchChunkRequest::class);
        $this->assertInstanceOf(Chunk::class, $response);
    }
}
