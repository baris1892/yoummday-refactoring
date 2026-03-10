<?php

declare(strict_types=1);

namespace App\Domain\Model;

class Token
{
    public function __construct(
        private readonly string $value,
        /** @var string[] */
        private readonly array  $permissions
    )
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }
}
