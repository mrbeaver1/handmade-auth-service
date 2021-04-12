<?php

namespace App\EventListener;

use App\Exception\ApiValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    /**
     * @param ExceptionEvent $event
     *
     * @return void
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ApiValidationException) {
            $responseErrors = [];

            foreach ($exception->getErrors() as $field => $errorMessage) {
                $fieldName = is_numeric($field) ? null : $field;

                $responseErrors['errors'][] = [
                    // В Api в это поле требуется передавать текстовый код ошибки, делаем это через message
                    'code' => 'validation-error',
                    'message' => "$fieldName: $errorMessage",
                    // Если ключ не содержит название поля, определяем field как null
                    'field' => $fieldName
                ];
            }

            $response = new JsonResponse(
                ['data' => $responseErrors],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }  else {
            $response = new JsonResponse(
                [
                    'data' => [
                        'errors' => [
                            [
                                'code' => 'internal-server-error',
                                'message' => $exception->getMessage(),
                                'line' => $exception->getLine(),
                                'file' => $exception->getFile(),
                            ],
                        ],
                    ],
                ],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        if (!empty($response)) {
            $event->setResponse($response);
        }
    }
}
