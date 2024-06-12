@extends('layouts.app')

@section('title', $pageTitle = 'لیست نوبت ها')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="/">داشبورد</a></li>
        <li class="breadcrumb-item active">{{ $pageTitle }}</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $pageTitle }}</h3>
                    {{--<a href="{{ route('reserve.create') }}" class="btn btn-primary btn-sm float-right">
                        افزودن
                        <span class="fa fa-plus"></span>
                    </a>--}}
                </div>
                <div class="card-body ">
                    <div class="row">
                        <form action="" method="GET" style="width: 100% !important;">
                            <div class="form-group col-12 pr-3 pl-3">
                                <label style="display: inline;" for="search">جستوجو</label>
                                <div class="input-group mb-3">
                                    <input style="border-bottom-right-radius: 5px; border-top-right-radius: 5px;" type="text" id="search" name="search" class="form-control" placeholder="کد ملی، موبایل، شماره شناسنامه" value="{{ request('search') }}">
                                    <div class="input-group-prepend">
                                        <button style="border-bottom-left-radius: 5px; border-top-left-radius: 5px;" class="btn btn-outline-secondary" type="submit">جستوجو</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                    <div class="alert alert-info">
                        نوبت های لیست زیر متعلق به تاریخ
                        <b class="convert-to-jalali">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $reserve->date)->timestamp }}</b>
                        از ساعت
                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $reserve->start_time)->format('H:i') }}
                        تا ساعت
                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $reserve->end_time)->format('H:i') }}
                        می باشد.
                    </div>
                    <table class="table table-bordered table-hover table-condensed small text-center">
                        <thead>
                        <tr>
                            <th>ردیف</th>
                            <th>نام و نام خانوادگی</th>
                            <th>موبایل</th>
                            <th>کد ملی</th>
                            <th>نوبت در صف</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($appointments) > 0)
                            @foreach($appointments as $index => $appointment)
                                @php $user = \App\Models\User::find($appointment->user_id); @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
                                    <td>{{ $user->mobile }}</td>
                                    <td>{{ $user->national_code }}</td>
                                    <td>{{ 'نفر ' . $appointment->position }}</td>
                                    <td>{{ $appointment->status_fa }}</td>
                                    <td>
                                        <form style="display: inline" action="{{ route('reserve.destroy-appointment', $appointment->id) }}" class="ajax-submit show-approve" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="redirect_to" value="{{ route('reserve.show', $reserve->id) }}">
                                            <button type="submit" class="btn btn-danger delete-reserve btn-sm m-1">
                                                حذف
                                                <span class="fa fa-trash"></span>
                                            </button>
                                        </form>
                                        <div class="form-group" style="display: inline" >
                                            <select class="form-control" onchange="changeStatus(this, '{{ $appointment->id }}')" style="width: 134px !important; display: inline">
                                                <option value="" disabled selected hidden>تغییر وضعیت</option>
                                                <option value="pending">معلق</option>
                                                <option value="done">تکمیل شده</option>
                                                <option value="rejected">رد شده</option>
                                                <option value="canceled">کنسل شده</option>
                                            </select>
                                            <form id="change_status_{{ $appointment->id }}" action="{{ route('change-status-appointment', $appointment->id) }}" method="POST">
                                                <input type="hidden" name="new_status" value="" id="new_status_{{ $appointment->id }}">
                                                @csrf
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">نوبتی موجود نمی باشد.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>

        function changeStatus(select, appointment) {
            let status = select.value;
            $(`#new_status_${appointment}`).val(status);
            document.getElementById('change_status_' + appointment).submit();
        }

        $(document).ready(function () {
            $('.convert-to-jalali').each(function () {
                let date_timestamp = $(this).text();
                $(this).text(new persianDate(parseInt(date_timestamp) * 1000).format('dddd D MMMM YYYY'));
            });
        });
    </script>
@endsection
