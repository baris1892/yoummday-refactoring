<?php

declare(strict_types=1);

namespace App\Service;

use App\Provider\TokenDataProvider;

/**
 * Domain service responsible for token permission resolution.
 *
 * Extracted from PermissionHandler to respect SRP: the handler's job is HTTP
 * translation, this class owns the business rule "does token X have permission Y?".
 * Keeping business logic here makes it reusable and unit-testable without any HTTP stack.
 */
class PermissionService
{
    /** Named constant instead of a magic string */
    private const PERMISSION_READ = 'read';

    /**
     * Note: In a real-world scenario, I would prefer injecting a TokenProviderInterface.
     * However, as per the task instructions to not modify the TokenDataProvider file,
     * I am injecting the concrete class here.
     */
    public function __construct(
        private readonly TokenDataProvider $tokenDataProvider,
    )
    {
    }

    public function hasRequiredPermission(
        string $tokenValue,
        string $permission = self::PERMISSION_READ
    ): bool
    {
        $token = $this->findToken($tokenValue);
        if ($token === null) {
            return false;
        }

        return in_array(
            $permission,
            $token['permissions']
        );
    }

    private function findToken(string $tokenValue): ?array
    {
        $tokens = $this->tokenDataProvider->getTokens();
        foreach ($tokens as $token) {
            // Ensure array structure is as expected before access
            if (isset($token['token']) && $token['token'] === $tokenValue) {
                return $token;
            }
        }

        return null;
    }
}
