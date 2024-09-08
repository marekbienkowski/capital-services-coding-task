<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\EventListener;

use App\Application\Exception\EntityNotFoundException;
use App\Domain\Common\Exception\DomainException;
use App\Infrastructure\API\Response\ErrorResponse;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;

#[AsEventListener(event: KernelEvents::EXCEPTION, method: 'onKernelException')]
class SymfonyExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        $statusCode = $this->matchExceptionTypeToStatusCode($throwable);

        $event->setResponse(
            new JsonResponse(
                new ErrorResponse(
                    message: !empty($throwable->getMessage())
                        ? $throwable->getMessage()
                        : Response::$statusTexts[$statusCode]
                ),
                $statusCode
            )
        );
    }

    private function matchExceptionTypeToStatusCode(Throwable $throwable): int
    {
        return match (true) {
            $throwable instanceof ValidationFailedException,
                $throwable instanceof DomainException => Response::HTTP_UNPROCESSABLE_ENTITY,
            $throwable instanceof EntityNotFoundException => Response::HTTP_NOT_FOUND,
            $throwable instanceof HttpException => $throwable->getStatusCode(),
            default => Response::HTTP_INTERNAL_SERVER_ERROR,
        };
    }
}
