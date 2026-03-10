<?php

declare(strict_types=1);

namespace Test\Service;

use App\Domain\Model\Token;
use App\Domain\Validator\PermissionValidator;
use App\Repository\TokenRepository;
use App\Service\PermissionService;
use PHPUnit\Framework\TestCase;

class PermissionServiceTest extends TestCase
{
    public function testIsTokenAuthorizedReturnsFalseWhenTokenNotFound(): void
    {
        $repo = $this->createMock(TokenRepository::class);
        $repo->method('findByValue')->willReturn(null);

        $service = new PermissionService($repo, new PermissionValidator());

        $this->assertFalse($service->isTokenAuthorized('invalid'));
    }

    public function testIsTokenAuthorizedDelegatesToValidator(): void
    {
        $token = new Token('valid', ['read']);
        $repo = $this->createMock(TokenRepository::class);
        $repo->method('findByValue')->with('valid')->willReturn($token);

        $service = new PermissionService($repo, new PermissionValidator());

        $this->assertTrue($service->isTokenAuthorized('valid', 'read'));
        $this->assertFalse($service->isTokenAuthorized('valid', 'write'));
    }
}
