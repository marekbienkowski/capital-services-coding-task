<?php

declare(strict_types=1);

namespace App\Infrastructure\API\Controller;

use App\Infrastructure\Security\JWT\CustomJwtTokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class JWTLoginController extends AbstractController
{
    private CustomJwtTokenGenerator $jwtTokenGenerator;

    public function __construct(CustomJwtTokenGenerator $jwtTokenGenerator)
    {
        $this->jwtTokenGenerator = $jwtTokenGenerator;
    }

    #[Route('/auth/token', name: 'api_get_token', methods: ['GET'])]
    public function getToken(): JsonResponse
    {
        // Get the JWT token
        $token = $this->jwtTokenGenerator->createToken(
            [
                'custom' => 'Some mocked data',
            ]
        );

        return new JsonResponse(['token' => $token]);
    }
}
