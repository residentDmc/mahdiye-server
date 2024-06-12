<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Helpers\SMSSender;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Auth\LoginRequest;
use App\Http\Requests\API\V1\Auth\SendCodeRequest;
use App\Http\Requests\API\V1\Auth\UserInformationRequest;
use App\Http\Resources\API\V1\UserInformationResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AuthenticationController extends Controller
{

    /**
     * @param LoginRequest $request
     * @return mixed
     */
    public function login(LoginRequest $request)
    {
        $mobile = $request->mobile;
        $code = $request->code;

        $user = User::whereMobile($mobile)->where('verify_code', '!=', null)->first();
        if ($user) {
            $codeExp = Carbon::createFromFormat('Y-m-d H:i:s', $user->verify_code_exp);
            if (!$codeExp->gte(now()))
                return Response::response(false, [], "کد ارسال شده منقضی شده است.", 400);

            if ($user->verify_code != $code)
                return Response::response(false, [], "کد ارسال شده صحیح نمی باشد.", 400);

            $user->update([
                'verify_code_exp' => now()
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
            $result = [
                'access_token' => $token,
                'information' => new UserInformationResource($user),
            ];
            return Response::response(true, $result, 'خوش آمدید.', 200);
        }
        return Response::response(false, [], "اطلاعات ارسالی معتبر نمی باشد.", 400);
    }

    /**
     * @param SendCodeRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function sendVerificationCode(SendCodeRequest $request)
    {
        $mobile = $request->mobile;
        $mobileVerifyCode = random_int(10000, 99999);
//        $mobileVerifyCode = 11111;
        $user = User::whereMobile($mobile)->first();
        if ($user) {
            $user->update(
                [
                    'verify_code' => $mobileVerifyCode,
                    'verify_code_exp' => now()->addMinutes(3)
                ]
            );
        } else {
            $user = User::create([
                'mobile' => $mobile,
                'verify_code' => $mobileVerifyCode,
                'verify_code_exp' => now()->addMinutes(3),
                'password' => Hash::make('1234')
            ]);
        }

        $sendCode = new SMSSender();
        $sendCode = $sendCode->sendVerificationCode($mobile, $mobileVerifyCode);
        if ($sendCode['status'])
            return Response::response(true, ['expiration_time' => 180], "کد احراز هویت به شماره موبایل " . $mobile . " پیامک شد.", 200);
        return Response::response(false, [], "خطا در اجرای عملیات", 500);
    }

    /**
     * @param UserInformationRequest $request
     * @return mixed
     */
    public function updateUserInformation(UserInformationRequest $request)
    {
        $user = auth()->user();
        $user->update($request->all());
        return Response::response(true, new UserInformationResource($user), 'عملیات با موفقیت انجام شد.', 200);
    }

    /**
     * @return mixed
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return Response::response(true, [], 'عملیات با موفقیت انجام شد.', 200);
    }
}
