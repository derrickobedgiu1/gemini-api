<?php

namespace Derrickob\GeminiApi\Tests\Fixtures\Forge;

use Saloon\Http\Faking\Fixture;

final class PermissionFixture extends Fixture
{
    protected function defineSensitiveJsonParameters(): array
    {
        return [
            'emailAddress' => 'redacted@example.com',
        ];
    }
}
