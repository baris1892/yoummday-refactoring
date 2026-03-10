<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\Model\Token;
use App\Provider\TokenDataProvider;

class TokenRepository
{
    public function __construct(
        private readonly TokenDataProvider $dataProvider
    )
    {
    }

    public function findByValue(string $value): ?Token
    {
        $data = $this->dataProvider->getTokens();

        foreach ($data as $tokenData) {
            if (($tokenData['token'] ?? null) === $value) {
                return new Token(
                    $tokenData['token'],
                    $tokenData['permissions'] ?? []
                );
            }
        }

        return null;
    }
}
