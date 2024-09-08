<?php

declare(strict_types=1);

namespace App\Infrastructure\API\Controller;

use App\Application\Command\CreateRepaymentScheduleCommand;
use App\Application\Command\DeactivateRepaymentScheduleCommand;
use App\Application\Query\GetLatestRelevantSchedulesQuery;
use App\Application\Query\GetRepaymentScheduleQuery;
use App\Application\Service\RepaymentScheduleService;
use App\Domain\RepaymentSchedule\Enum\ScheduleType;
use App\Domain\RepaymentSchedule\Model\Money;
use App\Domain\RepaymentSchedule\Model\RepaymentScheduleId;
use App\Infrastructure\API\Request\CreateRepaymentScheduleRequest;
use App\Infrastructure\API\Request\GetLatestRelevantSchedulesRequest;
use App\Infrastructure\API\Response\RepaymentScheduleResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'RepaymentScheduleManagement')]
#[Route('/api/repayment_schedule')]
class RepaymentScheduleController extends AbstractController
{
    public function __construct(
        private readonly RepaymentScheduleService $repaymentScheduleService,
    ) {
    }

    #[OA\Get(
        path: '/api/repayment_schedule/{id}',
        summary: 'Get a repayment schedule by ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Repayment Schedule ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns a repayment schedule',
                content: new OA\JsonContent(ref: new Model(type: RepaymentScheduleResponse::class))
            ),
            new OA\Response(
                response: 404,
                description: 'Repayment schedule not found'
            ),
        ]
    )]
    #[Security(name: 'Bearer')]
    #[Route(
        '/{id}',
        name: 'get_repayment_schedule',
        requirements: ['id' => '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}'],
        methods: [Request::METHOD_GET]
    )]
    public function getSingle(string $id): JsonResponse
    {
        $schedule = $this->repaymentScheduleService->getRepaymentSchedule(
            new GetRepaymentScheduleQuery(
                RepaymentScheduleId::fromString($id)
            )
        );

        return new JsonResponse(
            RepaymentScheduleResponse::fromEntity($schedule)
        );
    }

    #[OA\Get(
        path: '/api/repayment_schedule/latest-relevant',
        summary: 'Get latest relevant repayment schedules',
        parameters: [
            new OA\Parameter(
                name: 'excludeDeactivated',
                description: 'Whether to exclude deactivated schedules',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'boolean')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns a list of repayment schedules',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: RepaymentScheduleResponse::class))
                )
            ),
        ]
    )]
    #[Security(name: 'Bearer')]
    #[Route('/latest-relevant', name: 'get_relevant_repayment_schedules', methods: [Request::METHOD_GET])]
    public function getLatestRelevantSchedules(
        #[MapQueryString] GetLatestRelevantSchedulesRequest $requestDto,
    ): JsonResponse {
        $matchingSchedules = $this->repaymentScheduleService->getLatestRelevantSchedules(
            new GetLatestRelevantSchedulesQuery(
                includeDeactivated: !$requestDto->excludeDeactivated
            )
        );

        return new JsonResponse(
            RepaymentScheduleResponse::fromEntityCollection($matchingSchedules)
        );
    }

    #[OA\Post(
        path: '/api/repayment_schedule',
        summary: 'Create a new repayment schedule',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: new Model(type: CreateRepaymentScheduleRequest::class))
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Repayment schedule created',
                content: new OA\JsonContent(ref: new Model(type: RepaymentScheduleResponse::class))
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid input'
            ),
        ]
    )]
    #[Security(name: 'Bearer')]
    #[Route('/', name: 'create_payment_schedule', methods: [Request::METHOD_POST])]
    public function create(
        #[MapRequestPayload] CreateRepaymentScheduleRequest $requestDto,
    ): Response {
        $newSchedule = $this->repaymentScheduleService->createRepaymentSchedule(
            new CreateRepaymentScheduleCommand(
                amount: Money::fromPrimitives($requestDto->amount, $requestDto->currency),
                installmentCount: $requestDto->installmentCount,
                type: ScheduleType::ConstantInstallment
            )
        );

        return new JsonResponse(
            RepaymentScheduleResponse::fromEntity($newSchedule),
            Response::HTTP_CREATED
        );
    }

    #[OA\Delete(
        path: '/api/repayment_schedule/{id}',
        summary: 'Deactivate a repayment schedule',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Repayment Schedule ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid')
            ),
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Repayment schedule deactivated'
            ),
            new OA\Response(
                response: 404,
                description: 'Repayment schedule not found'
            ),
        ]
    )]
    #[Security(name: 'Bearer')]
    #[Route('/{id}', name: 'deactivate_repayment_schedule', methods: [Request::METHOD_DELETE])]
    public function deactivate(string $id): Response
    {
        $this->repaymentScheduleService->deactivateRepaymentSchedule(
            new DeactivateRepaymentScheduleCommand(
                id: RepaymentScheduleId::fromString($id)
            )
        );

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
