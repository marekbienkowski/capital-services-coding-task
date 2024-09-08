<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\JWT;

// src/Security/DummyUserProvider.php

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class DummyUserProvider implements UserProviderInterface
{
    public function loadUserByUsername(string $username): UserInterface
    {
        return new DummyUser($username);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof DummyUser) {
            throw new UnsupportedUserException();
        }

        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return DummyUser::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        // Returning a dummy user
        return new DummyUser($identifier);
    }
}
