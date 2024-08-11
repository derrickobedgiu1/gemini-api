<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Tests\Resources\TunedModels;

use Derrickob\GeminiApi\Data\Operation;
use Derrickob\GeminiApi\Requests\TunedModels\Operations\CancelOperationRequest;
use Derrickob\GeminiApi\Requests\TunedModels\Operations\GetOperationRequest;
use Derrickob\GeminiApi\Tests\TestCase;

final class TunedModelOperationsTest extends TestCase
{
    public function testCancel(): void
    {
        $this->mockResponse('tunedModels/operations/cancel');

        $operation = 'tunedModels/text-predictor-dsygc8rjuymz/operations/r6z44bgt2mog';
        $response = $this->gemini->tunedModels()->operations()->cancel($operation);

        $this->mockClient->assertSent(CancelOperationRequest::class);
        $this->assertTrue($response);
    }

    public function testGet(): void
    {
        $this->mockResponse('tunedModels/operations/get');

        $operation = 'tunedModels/text-predictor-dsygc8rjuymz/operations/r6z44bgt2mog';
        $response = $this->gemini->tunedModels()->operations()->get($operation);

        $this->mockClient->assertSent(GetOperationRequest::class);
        $this->assertInstanceOf(Operation::class, $response);
    }

    //    public function testList(): void
    //    {
    //        // Not tested. If you have a use-case, write the test to record fixture and create PR
    //    }
}
