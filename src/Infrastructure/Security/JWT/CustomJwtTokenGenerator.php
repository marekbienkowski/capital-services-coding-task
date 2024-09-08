<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\JWT;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class CustomJwtTokenGenerator
{

    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
        private UserProviderInterface $userProvider,
    ) {
    }

    public function createToken(array $payload = []): string
    {
        // Load the dummy user from the provider
        $dummyUser = $this->userProvider->loadUserByIdentifier('api_user');

        // Mocked JWT payload
        $defaultPayload = [
            'mock_data' => 'example_value',
            'role' => 'ROLE_API',
        ];

        // Merge any custom payload
        $finalPayload = array_merge($defaultPayload, $payload);

        // Generate the JWT token using the DummyUser
        return $this->jwtManager->createFromPayload($dummyUser, $finalPayload);
    }
}
