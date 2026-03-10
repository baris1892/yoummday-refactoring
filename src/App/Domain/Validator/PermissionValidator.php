<?php

declare(strict_types=1);

namespace App\Domain\Validator;

use App\Domain\Model\Token;

class PermissionValidator
{
    public function hasPermission(Token $token, string $requiredPermission): bool
    {
        return in_array(
            $requiredPermission,
            $token->getPermissions(),
            true
        );
    }
}
