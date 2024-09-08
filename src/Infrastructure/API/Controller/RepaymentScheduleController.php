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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

//REST API is usually only one of the access points to modern application. It is then an Infrastructure concern, that
// interacts with Application layer orchestrating Domain usage. I decided to not separate presentation Layer, as for
// different access points, presentation may differ.
#[Route('/api/repayment_schedule')]
class RepaymentScheduleController extends AbstractController
{
    public function __construct(
        private readonly RepaymentScheduleService $repaymentScheduleService,
    ) {
    }

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
