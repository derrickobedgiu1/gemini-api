<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Tests\Resources\Corpora;

use Derrickob\GeminiApi\Data\Corpus;
use Derrickob\GeminiApi\Requests\Corpora\CreateCorporaRequest;
use Derrickob\GeminiApi\Requests\Corpora\DeleteCorporaRequest;
use Derrickob\GeminiApi\Requests\Corpora\GetCorporaRequest;
use Derrickob\GeminiApi\Requests\Corpora\ListCorporaRequest;
use Derrickob\GeminiApi\Requests\Corpora\PatchCorporaRequest;
use Derrickob\GeminiApi\Requests\Corpora\QueryCorporaRequest;
use Derrickob\GeminiApi\Responses\Corpora\ListCorporaResponse;
use Derrickob\GeminiApi\Responses\Corpora\QueryCorporaResponse;
use Derrickob\GeminiApi\Tests\TestCase;

final class CorporaTest extends TestCase
{
    public function testCreate(): void
    {
        $this->mockResponse('corpora/create');

        $response = $this->gemini->corpora()->create(
            new Corpus(
                displayName: 'Saloon Test Corpus'
            )
        );

        $this->mockClient->assertSent('/corpora');
        $this->mockClient->assertSent(CreateCorporaRequest::class);
        $this->assertInstanceOf(Corpus::class, $response);
    }

    public function testDelete(): void
    {
        $this->mockResponse('corpora/delete');

        $response = $this->gemini->corpora()->delete([
            'name' => 'corpora/saloon-test-corpus-96ynlexks0pb',
        ]);

        $this->mockClient->assertSent(DeleteCorporaRequest::class);
        $this->assertTrue($response);
    }

    public function testGet(): void
    {
        $this->mockResponse('corpora/get');

        $response = $this->gemini->corpora()->get('corpora/test-corpus-j0oywm69m798');

        $this->mockClient->assertSent(GetCorporaRequest::class);
        $this->assertInstanceOf(Corpus::class, $response);
    }

    public function testList(): void
    {
        $this->mockResponse('corpora/list');

        $response = $this->gemini->corpora()->list();

        $this->mockClient->assertSent(ListCorporaRequest::class);
        $this->assertInstanceOf(ListCorporaResponse::class, $response);
    }

    public function testPatch(): void
    {
        $this->mockResponse('corpora/patch');

        $response = $this->gemini->corpora()->patch([
            'name' => 'corpora/saloon-test-corpus-96ynlexks0pb',
            'updateMask' => 'displayName',
            'corpus' => new Corpus(
                displayName: 'Updated Saloon Test Corpus'
            ),
        ]);

        $this->mockClient->assertSent(PatchCorporaRequest::class);
        $this->assertInstanceOf(Corpus::class, $response);
        $this->assertIsArray($response->toArray());
    }

    public function testQuery(): void
    {
        $this->mockResponse('corpora/query');

        $response = $this->gemini->corpora()->query([
            'name' => 'corpora/test-corpus-j0oywm69m798',
            'query' => 'sample',
        ]);

        $this->mockClient->assertSent(QueryCorporaRequest::class);
        $this->assertInstanceOf(QueryCorporaResponse::class, $response);
        $this->assertIsArray($response->toArray());
    }
}
