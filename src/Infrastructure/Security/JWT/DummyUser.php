<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\JWT;

use Symfony\Component\Security\Core\User\UserInterface;

class DummyUser implements UserInterface
{
    private string $username;

    public function __construct(string $username = 'api_user')
    {
        $this->username = $username;
    }

    public function getRoles(): array
    {
        return ['ROLE_API'];
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    public function getPassword(): ?string
    {
        return null;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function eraseCredentials(): void
    {
        // No-op
    }
}
