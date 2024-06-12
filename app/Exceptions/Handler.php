<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Throwable;
use Spatie\Permission\Exceptions\UnauthorizedException;


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

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param $request
     * @param ValidationException $exception
     * @return JsonResponse
     */
    protected function invalidJson($request, \Illuminate\Validation\ValidationException $exception): JsonResponse
    {
        return Response::response(false, ['errors' => $exception->errors()], 'خطا در اطلاعات ارسال شده.', $exception->status);
    }

    /**
     * @param $request
     * @param AuthenticationException $exception
     * @return \Illuminate\Http\Response|JsonResponse|RedirectResponse
     */
    protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception): \Illuminate\Http\Response|JsonResponse|\Illuminate\Http\RedirectResponse
    {
        if ($request->wantsJson() || $request->ajax())
            return Response::response(false, [], 'ابتدا وارد حساب کاربری شوید.', 401);

        return redirect()->route('login');
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof UnauthorizedException) {
            $errorMsg = "کاربر گرامی شما مجوز لازم برای مشاهده و بررسی این بخش از سامانه را ندارید. در صورت نیاز می توانید با مدیریت در ارتباط باشید.";

            abort(403, $errorMsg);
        }

        return parent::render($request, $exception);
    }
}
