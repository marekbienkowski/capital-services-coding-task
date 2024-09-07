<?php

declare(strict_types=1);

namespace App\API\Controller;

use App\API\Request\CreateRepaymentScheduleRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

//In general controllers any other access points are an infrastructure concern. However, I find it
// more readable and easier to navigate, when I separate access points from Infrastructure directory
#[Route('/api/payment_schedule')]
class RepaymentScheduleController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/', name: 'create_payment_schedule', methods: [Request::METHOD_POST])]
    public function create(
        #[MapRequestPayload] CreateRepaymentScheduleRequest $requestDto,
    ): Response {
        return new JsonResponse($requestDto);
    }
}
