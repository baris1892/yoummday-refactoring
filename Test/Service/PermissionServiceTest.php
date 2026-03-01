<?php

declare(strict_types=1);

namespace Test\Service;

use App\Provider\TokenDataProvider;
use App\Service\PermissionService;
use PHPUnit\Framework\TestCase;

class PermissionServiceTest extends TestCase
{
    public function testHasRequiredPermissionReturnsTrueOnMatch(): void
    {
        $mockProvider = $this->createMock(TokenDataProvider::class);
        $mockProvider->method('getTokens')->willReturn([
            ['token' => 'test-token', 'permissions' => ['read', 'write']]
        ]);

        $service = new PermissionService($mockProvider);

        $this->assertTrue(
            $service->hasRequiredPermission('test-token', 'read')
        );
    }

    public function testHasRequiredPermissionReturnsFalseOnMissingPermission(): void
    {
        $mockProvider = $this->createMock(TokenDataProvider::class);
        $mockProvider->method('getTokens')->willReturn([
            ['token' => 'readonly-token', 'permissions' => ['read']]
        ]);

        $service = new PermissionService($mockProvider);

        $this->assertFalse(
            $service->hasRequiredPermission('readonly-token', 'write')
        );
    }

    public function testHasRequiredPermissionReturnsFalseOnUnknownToken(): void
    {
        $mockProvider = $this->createMock(TokenDataProvider::class);
        $mockProvider->method('getTokens')->willReturn([]);

        $service = new PermissionService($mockProvider);

        $this->assertFalse(
            $service->hasRequiredPermission('non-existent')
        );
    }
}
