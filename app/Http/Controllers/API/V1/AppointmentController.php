<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\ReserveRequest;
use App\Http\Resources\API\V1\UserAppointmentResource;
use App\Models\Appointment;
use App\Models\Reserve;
use Illuminate\Support\Facades\Response;

class AppointmentController extends Controller
{


    public function appointments()
    {
        Reserve::where('used', null)->update(['used' => 0]);
        $reservesQuery = Reserve::where('status', 'active')->whereRaw("CONCAT(date, ' ', end_time) > now()")->whereColumn('capacity', '>', 'used')->get();
        $reservesId = $reservesQuery->pluck('id')->toArray();
        $reservesDates = array_unique( $reservesQuery->pluck('date')->toArray() );

        $appointments = [];
        foreach($reservesDates as $date) {
            $dateReserves = Reserve::whereIn('id', $reservesId)
                ->where('date', $date)
                ->get();
            $items = [];
            foreach($dateReserves as $recordItem) {
                $items[] = [
                    'capacity' => $recordItem->capacity - $recordItem->used,
                    'id' => $recordItem->id,
                    'start_time' => $recordItem->start_time,
                    'end_time' => $recordItem->end_time,
                ];
            }
            $appointments[] = [
                'date' => $date,
                'items' => $items
            ];
        }

        return Response::response(true,
            ['appointments' => $appointments, 'user_auth' => auth('sanctum')->id()],
            count($appointments) > 0
                ? "یکی از نوبت های موجود را انتخاب کنید."
                : "در حال حاضر نوبتی موجود نمی باشد، لطفا در زمانی دیگر اقدام کنید.",
            200);
    }

    public function getAppointment(ReserveRequest $request)
    {
        $user = auth('sanctum')->user();
        if (!$this->hasProfile($user)) {
            return Response::response(false, [], "لطفا مشخصات کاربری خود را تکمیل کنید.", 405);
        }
        $reserve = Reserve::find($request->reserve_id);
        if ($reserve->appointments()->where('user_id', $user->id)->first()) {
            return Response::response(false, [], "برای تاریخ " . \Morilog\Jalali\Jalalian::forge($reserve->date)->format('Y/m/d') . " نوبت ثبت کرده اید.", 405);
        }

        $hasPendingAppointment = Appointment::where('user_id', $user->id)->where('status', 'pending')->count();
        if ($hasPendingAppointment >= 1) {
            return Response::response(false, [], "شما پیش از این یک نوبت با وضعیت معلق ثبت کرده اید.", 405);
        }

        $reserveCapacity = Appointment::where('reserve_id', $reserve->id)->count();
        if ($reserve->used >= $reserve->capacity) {
            return Response::response(false, [], "برای تاریخ " . \Morilog\Jalali\Jalalian::forge($reserve->date)->format('Y/m/d') . " امکان ثبت نوبت موجود نمی باشد.", 405);
        }
        $reserve->increment('used');
        $newAppointment = $reserve->appointments()->create([
            'user_id' => $user->id,
            'position' => $reserve->used,
            'status' => 'pending'
        ]);
        return Response::response(true, ['appointment' => new UserAppointmentResource($newAppointment)], "نوبت شما برای تاریخ " . \Morilog\Jalali\Jalalian::forge($reserve->date)->format('Y/m/d') . " با موفقیت ثبت شد", 200);
    }

    private function hasProfile($user)
    {
        foreach ($user->only('national_code', 'first_name', 'last_name') as $column => $value) {
            if ($value == null || $value == "") {
                return false;
            }
        }
        return true;
    }
}
