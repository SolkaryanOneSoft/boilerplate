<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $e): JsonResponse
    {
        if ($e instanceof ValidationException) {
            return $this->handleValidationException($e);
        }

        if ($e instanceof CustomErrorException) {
            return $e->render($request);
        }

        if ($e instanceof AuthenticationException) {
            return response()->json([
                'error' => [
                    'messages' => ['Unauthenticated.']
                ],
            ], 401);
        }

        return response()->json([
            'error' => [
                'messages' => [$e->getMessage()],
            ],
        ], 500);
    }

    protected function handleValidationException(ValidationException $exception): JsonResponse
    {
        $errors = $exception->errors();

        $firstError = reset($errors);

        return response()->json([
            'error' => [
                'messages' => $firstError
            ]
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
