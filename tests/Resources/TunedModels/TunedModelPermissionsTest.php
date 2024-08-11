<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Tests\Resources\TunedModels;

use Derrickob\GeminiApi\Data\Permission;
use Derrickob\GeminiApi\Enums\GranteeType;
use Derrickob\GeminiApi\Enums\PermissionRole;
use Derrickob\GeminiApi\Requests\TunedModels\Permissions\CreatePermissionRequest;
use Derrickob\GeminiApi\Requests\TunedModels\Permissions\DeletePermissionRequest;
use Derrickob\GeminiApi\Requests\TunedModels\Permissions\GetPermissionRequest;
use Derrickob\GeminiApi\Requests\TunedModels\Permissions\ListPermissionsRequest;
use Derrickob\GeminiApi\Requests\TunedModels\Permissions\PatchPermissionRequest;
use Derrickob\GeminiApi\Responses\ListPermissionsResponse;
use Derrickob\GeminiApi\Tests\Fixtures\Forge\PermissionFixture;
use Derrickob\GeminiApi\Tests\TestCase;

final class TunedModelPermissionsTest extends TestCase
{
    public function testCreate(): void
    {
        $this->mockResponse(
            new PermissionFixture('tunedModels/permissions/create')
        );

        $response = $this->gemini->tunedModels()->permissions()->create([
            'parent' => 'tunedModels/text-predictor-dsygc8rjuymz',
            'permission' => new Permission(
                role: PermissionRole::READER,
                granteeType: GranteeType::GROUP,
                emailAddress: 'genai-samples-test-group@googlegroups.com',
            ),
        ]);

        $this->mockClient->assertSent(CreatePermissionRequest::class);
        $this->assertInstanceOf(Permission::class, $response);
    }

    public function testDelete(): void
    {
        $this->mockResponse('tunedModels/permissions/delete');

        $response = $this->gemini->tunedModels()->permissions()->delete('tunedModels/text-predictor-dsygc8rjuymz/permissions/101799614406133382015');

        $this->mockClient->assertSent(DeletePermissionRequest::class);
        $this->assertTrue($response);
    }

    public function testGet(): void
    {
        $this->mockResponse(
            new PermissionFixture('tunedModels/permissions/get')
        );

        $permission = 'tunedModels/text-predictor-dsygc8rjuymz/permissions/101799614406133382015';
        $response = $this->gemini->tunedModels()->permissions()->get($permission);

        $this->mockClient->assertSent(GetPermissionRequest::class);
        $this->assertInstanceOf(Permission::class, $response);
    }

    public function testList(): void
    {
        $this->mockResponse(
            new PermissionFixture('tunedModels/permissions/list')
        );

        $response = $this->gemini->tunedModels()->permissions()->list([
            'parent' => 'tunedModels/text-predictor-dsygc8rjuymz',
        ]);

        $this->mockClient->assertSent(ListPermissionsRequest::class);
        $this->assertInstanceOf(ListPermissionsResponse::class, $response);
    }

    public function testPatch(): void
    {
        $this->mockResponse(
            new PermissionFixture('tunedModels/permissions/patch')
        );

        $response = $this->gemini->tunedModels()->permissions()->patch([
            'name' => 'tunedModels/text-predictor-dsygc8rjuymz/permissions/101799614406133382015',
            'updateMask' => 'role',
            'permission' => new Permission(
                role: PermissionRole::WRITER,
                granteeType: GranteeType::GROUP,
                emailAddress: 'genai-samples-test-group@googlegroups.com',
            ),
        ]);

        $this->mockClient->assertSent(PatchPermissionRequest::class);
        $this->assertInstanceOf(Permission::class, $response);
    }
}
