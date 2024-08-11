<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Tests\Resources\Corpora;

use Derrickob\GeminiApi\Data\Permission;
use Derrickob\GeminiApi\Enums\GranteeType;
use Derrickob\GeminiApi\Enums\PermissionRole;
use Derrickob\GeminiApi\Requests\Corpora\Permissions\CreatePermissionRequest;
use Derrickob\GeminiApi\Requests\Corpora\Permissions\DeletePermissionRequest;
use Derrickob\GeminiApi\Requests\Corpora\Permissions\GetPermissionRequest;
use Derrickob\GeminiApi\Requests\Corpora\Permissions\ListPermissionRequest;
use Derrickob\GeminiApi\Requests\Corpora\Permissions\PatchPermissionRequest;
use Derrickob\GeminiApi\Responses\ListPermissionsResponse;
use Derrickob\GeminiApi\Tests\Fixtures\Forge\PermissionFixture;
use Derrickob\GeminiApi\Tests\TestCase;

final class CorporaPermissionsTest extends TestCase
{
    public function testCreate(): void
    {
        $this->mockResponse(
            new PermissionFixture('corpora/permissions/create')
        );

        $response = $this->gemini->corpora()->permissions()->create([
            'parent' => 'corpora/test-corpus-j0oywm69m798',
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
        $this->mockResponse('corpora/permissions/delete');

        $response = $this->gemini->corpora()->permissions()->delete('corpora/test-corpus-j0oywm69m798/permissions/101799614406133382015');

        $this->mockClient->assertSent(DeletePermissionRequest::class);
        $this->assertTrue($response);
    }

    public function testGet(): void
    {
        $this->mockResponse(
            new PermissionFixture('corpora/permissions/get')
        );

        $permission = 'corpora/test-corpus-j0oywm69m798/permissions/101799614406133382015';
        $response = $this->gemini->corpora()->permissions()->get($permission);

        $this->mockClient->assertSent(GetPermissionRequest::class);
        $this->assertInstanceOf(Permission::class, $response);
    }

    public function testList(): void
    {
        $this->mockResponse(
            new PermissionFixture('corpora/permissions/list')
        );

        $response = $this->gemini->corpora()->permissions()->list([
            'parent' => 'corpora/test-corpus-j0oywm69m798',
        ]);

        $this->mockClient->assertSent(ListPermissionRequest::class);
        $this->assertInstanceOf(ListPermissionsResponse::class, $response);
    }

    public function testPatch(): void
    {
        $this->mockResponse(
            new PermissionFixture('corpora/permissions/patch')
        );

        $response = $this->gemini->corpora()->permissions()->patch([
            'name' => 'corpora/test-corpus-j0oywm69m798/permissions/101799614406133382015',
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
