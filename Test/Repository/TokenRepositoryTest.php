<?php

namespace Test\Repository;

use App\Domain\Model\Token;
use App\Provider\TokenDataProvider;
use App\Repository\TokenRepository;
use PHPUnit\Framework\TestCase;

class TokenRepositoryTest extends TestCase
{
    public function testFindByValueReturnsTokenObject(): void
    {
        $mockProvider = $this->createMock(TokenDataProvider::class);
        $mockProvider->method('getTokens')->willReturn([
            ['token' => 'secure-token', 'permissions' => ['read']]
        ]);

        $repository = new TokenRepository($mockProvider);
        $result = $repository->findByValue('secure-token');

        $this->assertInstanceOf(Token::class, $result);
        $this->assertSame('secure-token', $result->getValue());
        $this->assertSame(['read'], $result->getPermissions());
    }

    public function testFindByValueReturnsNullIfNotFound(): void
    {
        $mockProvider = $this->createMock(TokenDataProvider::class);
        $mockProvider->method('getTokens')->willReturn([]);

        $repository = new TokenRepository($mockProvider);
        $this->assertNull($repository->findByValue('unknown'));
    }
}
