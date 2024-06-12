<?php

namespace App\Http\Controllers\Web;

use App\Helpers\SMSSender;
use App\Http\Controllers\Controller;
use App\Http\Requests\WEB\ReserveRequest;
use App\Models\Appointment;
use App\Models\Reserve;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Morilog\Jalali\Jalalian;

class ReserveController extends Controller
{


    public function __construct()
    {
        $this->middleware(['permission:show_reserve'])->only('index');
        $this->middleware(['permission:update_reserve'])->only(['edit', 'update']);
        $this->middleware(['permission:delete_reserve'])->only('destroy');
        $this->middleware(['permission:add_reserve_once'])->only(['create', 'store']);
        $this->middleware(['permission:add_reserve_multiple'])->only(['createMultiple', 'storeMultiple']);
        $this->middleware(['permission:show_appointment'])->only(['show', 'allAppointments']);
        $this->middleware(['permission:delete_appointment'])->only('destroyAppointment');
        $this->middleware(['permission:change_status_appointment'])->only('changeAppointmentStatus');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reserveQuery = Reserve::with('appointments')
            ->where(function ($query) {
                $filter = request('filter');
                if (strlen($filter) > 0 && in_array($filter, ['inactive', 'active', 'expired'])) {
                    $query->where('status', $filter);
                }
                if ($filter == "today") {
                    $query->where('date', now()->format('Y-m-d'));
                }
            })
            ->orderBy('date', 'asc')
            ->orderBy('start_time');
        if (request('get-report')) {
            $reserves = $reserveQuery->get();
            return $this->getReport($reserves, 'reserves');
        }
        $reserves = $reserveQuery->paginate(15);
        return view('reserve.index', compact('reserves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reserve.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReserveRequest $request)
    {
        $reserve = Reserve::create($request->all());
        return response()->json(['message' => 'رزرو با موفقیت ذخیره شد.', 'redirect' => route('reserve.index')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reserve = Reserve::find($id);
        if (!$reserve)
            return redirect()->route('reserve.index')->withErrors(['رزرو مورد نظر یافت نشد.']);

        $search = request('search');
        if ($search) {
            $appointmentUsers = Appointment::where('reserve_id', $reserve->id)->pluck('user_id')->toArray();
            $searchedUsers = User::whereIn('id', $appointmentUsers)->where(function ($query) use ($search) {
                $query
                    ->orWhere('mobile', 'LIKE', '%' . $search . '%')
                    ->orWhere('national_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('certificate_number', 'LIKE', '%' . $search . '%');
            })->pluck('id')->toArray();
            $appointments = Appointment::whereIn('user_id', $searchedUsers)->where('reserve_id', $reserve->id)->get();
        } else {
            $appointments = Appointment::where('reserve_id', $reserve->id)->get();
        }
        return view('reserve.appointments', compact('reserve', 'appointments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $reserve = Reserve::find($id);
        if (!$reserve)
            return redirect()->route('reserve.index')->withErrors(['رزرو مورد نظر یافت نشد.']);
        return view('reserve.edit', compact('reserve'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReserveRequest $request, string $id)
    {
        $reserve = Reserve::find($id);
        $reserve->update($request->all());
        return response()->json(['message' => 'رزرو با موفقیت ذخیره شد.', 'redirect' => route('reserve.edit', $reserve->id)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reserve = Reserve::find($id);
        if ($reserve) {
            $reserve->appointments()->delete();
            $reserve->delete();
            return response()->json(['message' => 'رزرو با موفقیت حذف شد.', 'redirect' => route('reserve.index')]);
        }
        return redirect()->route('reserve.index')->withErrors(['رزرو مورد نظر موجود نمی باشد.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyAppointment(string $id)
    {
        $appointment = Appointment::find($id);
        $redirectTo = request('redirect_to');
        if ($appointment) {
            $reserveId = $appointment->reserve_id;
            if ($appointment->status == "pending")
                Reserve::find($reserveId)->decrement('used');
            $appointment->delete();
            return response()->json(['message' => 'نوبت با موفقیت حذف شد.', 'redirect' => $redirectTo]);
        }
        return response()->json(['message' => 'رزرو مورد نظر موجود نمی باشد.', 'redirect' => $redirectTo]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function appointmentSummary(Request $request): JsonResponse
    {
        $appointment = Appointment::with(['user', 'reserve'])->find($request->id);
        $times = date("H:i", strtotime($appointment->reserve->start_time)) . ' تا ' . date("H:i", strtotime($appointment->reserve->end_time));
        $appointment['reserve_date'] = Jalalian::forge($appointment->reserve->date)->format('%A, %d %B %Y') . " | " . $times;
        if ($appointment->detail != null)
            $appointment['loan_amount'] = number_format($appointment->detail['loan_amount']) . " ریال";
        return response()->json(['appointment' => $appointment]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function changeAppointmentStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sms' => [
                'required',
                'in:0,1,2,3,4'
            ],
            'status' => [
                'required',
                'in:rejected,done,canceled,checked'
            ],
            'loan_amount' => [
                'required_if:sms,4',
            ],
            'appointment_id' => [
                'required',
                'exists:appointments,id'
            ],
            'reuse' => [
                'nullable',
                'in:0,1'
            ]
        ],[
            'sms.required' => 'پیامک ارسالی را انتخاب کنید.',
            'sms.in' => 'پیامک ارسالی معتبر نمی باشد.',
            'status.in' => 'وضعیت انتخاب شده معتبر نمی باشد.',
            'reuse.in' => 'گزینه انتخاب شده برای استفاده مجدد نوبت، معتبر نمی باشد.',
            'loan_amount.required_if' => 'مبلغ وام را وارد کنید.',
            'status.required' => 'وضعیت را انتخاب کنید.',
            'appointment_id.required' => 'خطا در اطلاعات ارسال شده.',
            'appointment_id.exists' => 'خطا در اطلاعات ارسال شده.',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $appointment = Appointment::where('deleted_at', null)->where('id', $request->appointment_id)->first();
        if (!$appointment) {
            return response()->json(['error' => 'نوبت مورد نظر حذف شده است.'], 400);
        }

        switch ($request->sms) {
            case '1':
                $sendCode = new SMSSender();
                $sendCode = $sendCode->sendVerificationCode(User::find($appointment->user_id)->mobile, "بد حسابی", "loanres");
                break;

            case '2':
                $sendCode = new SMSSender();
                $sendCode = $sendCode->sendVerificationCode(User::find($appointment->user_id)->mobile, "داشتن ضمانت", "loanres");
                break;

            case '3':
                $sendCode = new SMSSender();
                $sendCode = $sendCode->sendVerificationCode(User::find($appointment->user_id)->mobile, "داشتن بدهی", "loanres");
                break;

            case '4':
                $sendCode = new SMSSender();
                $sendCode = $sendCode->sendVerificationCode(User::find($appointment->user_id)->mobile, number_format($request->loan_amount), "acceptloan");
                break;

            case '0':
                $sendCode = ['status' => true];
                break;

            default:
                $sendCode = ['status' => false];
                break;
        }

        if ($sendCode['status']) {
            if ($request->reuse && $appointment->status == "pending")
                Reserve::find($appointment->reserve_id)->decrement('used');


            if ($request->sms == "4") {
                $detail = $appointment->detail;
                if (is_null($detail)) {
                    $detail = ['loan_amount' => $request->loan_amount];
                } else {
                    $detail['loan_amount'] = $request->loan_amount;
                }
                $appointment->update([
                    'status' => $request->status,
                    'detail' => $detail
                ]);
            } else {
                $detail = $appointment->detail;
                if (!is_null($detail)) {
                    $appointment->update([
                        'status' => $request->status,
                        'detail' => null
                    ]);
                } else {
                    $appointment->update(['status' => $request->status]);
                }
            }

            return response()->json(['message' => 'عملیات با موفقیت انجام شد.'], 200);
        }
        return response()->json(['error' => 'خطا در انجام عملیات، مجددا تلاش کنید.'], 500);
    }

    public function createMultiple()
    {
        return view('reserve.create-multiple');
    }

    public function storeMultiple(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->fields['time_records'] as $timePeriod) {
                foreach (CarbonPeriod::create($request->fields['start_date'], $request->fields['end_date'])->toArray() as $date) {
                    Reserve::create([
                        'date' => $date->format('Y-m-d'),
                        'start_time' => $timePeriod['start_time'],
                        'end_time' => $timePeriod['end_time'],
                        'capacity' => $timePeriod['capacity'],
                        'status' => $timePeriod['status']
                    ]);
                }
            }
            DB::commit();
            return response()->json(['data' => null, 'error' => null], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['data' => null, 'error' => $e->getMessage()], 500);
        }
    }

    public function allAppointments()
    {
        $search = request('search');
        $usersInSearch = User::where(function ($query) use($search) {
            if ($search) {
                $query
                    ->orWhere('mobile', 'LIKE', '%' . $search . '%')
                    ->orWhere('national_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('certificate_number', 'LIKE', '%' . $search . '%');
            }
        })->pluck('id')->toArray();

        $reservesInSearch = Reserve::where(function ($query) {
            if (request('from')) {
                $query->whereDate('date', '>=', request('from'));
            }
            if (request('to')) {
                $query->whereDate('date', '<=', request('to'));
            }
        })->pluck('id')->toArray();

        $appointments = Appointment::with('reserve')->with('user')
            ->where(function ($query) use ($reservesInSearch, $usersInSearch) {
                $query->whereIn('user_id', $usersInSearch);
                $query->whereIn('reserve_id', $reservesInSearch);
                if (in_array(request('status'), ['canceled', 'checked', 'rejected', 'done', 'pending'])) {
                    $query->where('status', request('status'));
                }
            })->get();

        if (request('get-report')) {
            return $this->getReport($appointments, 'appointments');
        }

        if (strtolower(request('date-sort') == "asc")) {
            $appointments = $appointments->sortBy(function ($appointment) {
                return $appointment->reserve->date;
            });
        } elseif (strtolower(request('date-sort') == "desc")) {
            $appointments = $appointments->sortByDesc(function ($appointment) {
                return $appointment->reserve->date;
            });
        }
        $appointments = $appointments->values();
        $appointments = $appointments->paginate(15);
        return view('reserve.all-appointments', compact('appointments'));
    }

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
