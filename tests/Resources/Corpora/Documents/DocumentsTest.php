<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Tests\Resources\Corpora\Documents;

use Derrickob\GeminiApi\Data\Document;
use Derrickob\GeminiApi\Requests\Corpora\Documents\CreateDocumentRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\DeleteDocumentRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\GetDocumentRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\ListDocumentRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\PatchDocumentRequest;
use Derrickob\GeminiApi\Requests\Corpora\Documents\QueryDocumentRequest;
use Derrickob\GeminiApi\Responses\Corpora\Documents\ListDocumentResponse;
use Derrickob\GeminiApi\Responses\Corpora\Documents\QueryDocumentResponse;
use Derrickob\GeminiApi\Tests\TestCase;

final class DocumentsTest extends TestCase
{
    public function testCreate(): void
    {
        $this->mockResponse('corpora/documents/create');

        $response = $this->gemini->corpora()->documents()->create([
            'parent' => 'corpora/test-corpus-j0oywm69m798',
            'document' => new Document(
                displayName: 'Saloon Sample Document'
            ),
        ]);

        $this->mockClient->assertSent(CreateDocumentRequest::class);
        $this->assertInstanceOf(Document::class, $response);
    }

    public function testDelete(): void
    {
        $this->mockResponse('corpora/documents/delete');

        $response = $this->gemini->corpora()->documents()->delete([
            'name' => 'corpora/test-corpus-j0oywm69m798/documents/saloon-sample-document-w0myparwabba',
        ]);

        $this->mockClient->assertSent(DeleteDocumentRequest::class);
        $this->assertTrue($response);
    }

    public function testQuery(): void
    {
        $this->mockResponse('corpora/documents/query');

        $response = $this->gemini->corpora()->documents()->query([
            'name' => 'corpora/test-corpus-j0oywm69m798/documents/test-document-rl76h09upqj3',
            'query' => 'test',
        ]);

        $this->mockClient->assertSent(QueryDocumentRequest::class);
        $this->assertInstanceOf(QueryDocumentResponse::class, $response);
    }

    public function testGet(): void
    {
        $this->mockResponse('corpora/documents/get');

        $response = $this->gemini->corpora()->documents()->get('corpora/test-corpus-j0oywm69m798/documents/test-document-rl76h09upqj3');

        $this->mockClient->assertSent(GetDocumentRequest::class);
        $this->assertInstanceOf(Document::class, $response);
    }

    public function testPatch(): void
    {
        $this->mockResponse('corpora/documents/patch');

        $response = $this->gemini->corpora()->documents()->patch([
            'name' => 'corpora/test-corpus-j0oywm69m798/documents/test-document-rl76h09upqj3',
            'updateMask' => 'displayName',
            'document' => new Document(
                displayName: 'Document name updated'
            ),
        ]);

        $this->mockClient->assertSent(PatchDocumentRequest::class);
        $this->assertInstanceOf(Document::class, $response);
    }

    public function testList(): void
    {
        $this->mockResponse('corpora/documents/list');

        $response = $this->gemini->corpora()->documents()->list([
            'parent' => 'corpora/test-corpus-j0oywm69m798',
        ]);

        $this->mockClient->assertSent(ListDocumentRequest::class);
        $this->assertInstanceOf(ListDocumentResponse::class, $response);
    }
}
