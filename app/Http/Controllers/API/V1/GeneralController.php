<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\UserAppointmentResource;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class GeneralController extends Controller
{


    public function privacyPolicy()
    {
        $setting = Setting::where('key', 'privacy_policy')->first();
        if (!$setting)
            $content = "";
        else
            $content = $setting->value;

        return Response::response(true, ['privacy_policy' => $content], 'اطلاعات با موفقیت دریافت شد.', 200);
    }

    public function userAppointments()
    {
        $user = Auth::user();

        $appointments = $user->appointments;

        return Response::response(true, ['appointments' => UserAppointmentResource::collection($appointments)], "اطلاعات با موفقیت دریافت شدند.", 200);

    }
}
