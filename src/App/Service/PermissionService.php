<?php

declare(strict_types=1);

namespace App\Service;

use App\Domain\Validator\PermissionValidator;
use App\Repository\TokenRepository;

class PermissionService
{
    private const DEFAULT_PERMISSION = 'read';

    public function __construct(
        private readonly TokenRepository     $tokenRepository,
        private readonly PermissionValidator $permissionValidator
    )
    {
    }

    public function isTokenAuthorized(
        string $tokenValue,
        string $permission = self::DEFAULT_PERMISSION
    ): bool
    {
        $token = $this->tokenRepository->findByValue($tokenValue);
        if ($token === null) {
            return false;
        }

        return $this->permissionValidator->hasPermission($token, $permission);
    }
}
