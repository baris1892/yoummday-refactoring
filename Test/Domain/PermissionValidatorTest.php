<?php

namespace Test\Domain\Validator;

use App\Domain\Model\Token;
use App\Domain\Validator\PermissionValidator;
use PHPUnit\Framework\TestCase;

class PermissionValidatorTest extends TestCase
{
    public function testHasPermissionReturnsTrueIfPresent(): void
    {
        $validator = new PermissionValidator();
        $token = new Token('unit-test', ['read', 'write']);

        $this->assertTrue($validator->hasPermission($token, 'read'));
    }

    public function testHasPermissionReturnsFalseIfMissing(): void
    {
        $validator = new PermissionValidator();
        $token = new Token('unit-test', ['read']);

        $this->assertFalse($validator->hasPermission($token, 'write'));
    }
}
