@extends('layouts.app')

@section('title', $pageTitle = 'لیست همه نوبت ها')

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
                    <a href="{{ request()->fullUrlWithQuery(['get-report' => 1]) }}" class="btn btn-primary btn-sm float-right">
                        خروجی اکسل
                        <span class="fa fa-print"></span>
                    </a>
                </div>
                <div class="card-body ">
                    <div class="row">
                        <form action method="GET" class="row col-12 " autocomplete="off">
                            <div class="form-group col-lg-3 col-sm-12 pr-3 pl-3">
                                <div class="input-group mb-3">
                                    <input type="text" id="search" name="search" class="form-control" placeholder="کد ملی، موبایل، شماره شناسنامه" value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="form-group col-lg-3 col-sm-12 pr-3 pl-3">
                                <input type="text" id="from_date" data-name="from-inp" class="form-control persian-datepicker" >
                            </div>
                            <div class="form-group col-lg-3 col-sm-12 pr-3 pl-3">
                                <input type="text" id="to_date" data-name="to-inp" class="form-control persian-datepicker">
                            </div>
                            <div class="form-group col-lg-2 col-sm-12 pr-3 pl-3">
                                <select name="status" id="stts" class="form-control">
                                    <option value="" selected disabled> وضعیت </option>
                                    <option value> همه </option>
                                    <option @selected(request('status') == "rejected") value="rejected" > رد شده </option>
                                    <option @selected(request('status') == "checked") value="checked" > بررسی شده </option>
                                    <option @selected(request('status') == "done") value="done" > تکمیل شده </option>
                                    <option @selected(request('status') == "pending") value="pending" > در انتظار بررسی </option>
                                    <option @selected(request('status') == "canceled") value="canceled" > کنسل شده </option>
                                </select>
                            </div>
                            <div class="form-group col-lg-1 col-sm-12 pr-3 pl-3">
                                <input type="hidden" name="from" id="from-inp" value="{{ request('from', null) }}">
                                <input type="hidden" name="to" id="to-inp" value="{{ request('to', null) }}">
                                <input type="hidden" name="date-sort" id="date-sort" value="{{ request('date-sort') }}">
                                <button title="جستوجو" type="submit" class="btn btn-success">
                                    <span class="fa fa-search"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-condensed small text-center">
                            <thead>
                            <tr>
                                <th>ردیف</th>
                                <th>نام و نام خانوادگی</th>
                                <th>موبایل</th>
                                <th>کد ملی</th>
                                <th style="cursor: pointer;" data-sort="{{ in_array(request('date-sort'), ['asc', 'desc']) ? request('date-sort') : '' }}" onclick="changeSort(this)">
                                    تاریخ
                                    <i class="right fas fa-angle-left" style="float: left; padding-top: 5px; transform: rotate({{ request('date-sort') == 'desc' ? '-90' : '90' }}deg);"></i>
                                </th>
                                <th>زمان</th>
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
                                        <td>{{ \Morilog\Jalali\Jalalian::forge($appointment->reserve->date)->format('Y/m/d') }}</td>
                                        <td>{{ date("H:i", strtotime($appointment->reserve->start_time)) . ' تا ' . date("H:i", strtotime($appointment->reserve->end_time)) }}</td>
                                        <td>{{ 'نفر ' . $appointment->position }}</td>
                                        <td>{{ $appointment->status_fa }}</td>
                                        <td>
                                            {{--<form style="display: inline" action="{{ route('reserve.destroy-appointment', $appointment->id) }}" class="ajax-submit show-approve" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="redirect_to" value="{{ route('all-appointments') }}">
                                                <button type="submit" class="btn btn-danger delete-reserve btn-sm m-1">
                                                    حذف
                                                    <span class="fa fa-trash"></span>
                                                </button>
                                            </form>--}}
                                            <button type="button" data-app_id="{{ $appointment->id }}" onclick="editAppointmentModal(this)" class="btn btn-outline-primary btn-sm m-1">
                                                ویرایش
                                                <span class="fa fa-edit"></span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" class="text-center">نوبتی موجود نمی باشد.</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                        {{ $appointments->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="editAppointmentModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش نوبت</h5>
                    <button type="button" class="close" data-dismiss="modal" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info" style="background-color: #1ab12b8a !important;">
                        <h3>جزئیات نوبت:</h3>
                        <p style="margin-bottom: 0 !important; margin-top: 0 !important;">
                            <b>نام و نام خانوادگی کاربر: </b>
                            <span class="cstm-span-mdl" id="mdl-usr-fullname"></span>
                        </p>
                        <p style="margin-bottom: 0 !important; margin-top: 0 !important;">
                            <b>کد ملی کاربر: </b>
                            <span class="cstm-span-mdl" id="mdl-usr-ntionlcode"></span>
                        </p>
                        <p style="margin-bottom: 0 !important; margin-top: 0 !important;">
                            <b>موبایل کاربر: </b>
                            <span class="cstm-span-mdl" id="mdl-usr-mbil"></span>
                        </p>
                        <p style="margin-bottom: 0 !important; margin-top: 0 !important;">
                            <b>وضعیت فعلی نوبت: </b>
                            <span class="cstm-span-mdl" id="mdl-apnt-sts"></span>
                        </p>
                        <p style="margin-bottom: 0 !important; margin-top: 0 !important;">
                            <b>تاریخ نوبت: </b>
                            <span class="cstm-span-mdl" id="mdl-apnt-date"></span>
                        </p>
                        <p style="margin-bottom: 0 !important; margin-top: 0 !important;">
                            <b>نوبت در صف: </b>
                            <span class="cstm-span-mdl" id="mdl-apnt-pztn"></span>
                        </p>
                    </div>
                    <div class="alert alert-info" id="mdl-apnt-loan-amnt-sec" style="background-color: #1ab12b8a !important;">
                        <p style="margin-bottom: 0 !important; margin-top: 0 !important;">
                            <b>مبلغ وام مورد تایید: </b>
                            <span class="cstm-span-mdl" id="mdl-apnt-loan-amnt"></span>
                        </p>
                    </div>
                    <hr>
                    <form id="mdl-form">
                        <div class="form-group row">
                            <label class="col-lg-2 col-sm-12 col-form-label">وضعیت: </label>
                            <div class="col-lg-10 col-sm-12">
                                <select name="status" class="form-control" name="status">
                                    <option value="" selected disabled >انتخاب وضعیت</option>
                                    <option value="rejected">رد شده</option>
                                    <option value="checked">بررسی شده</option>
                                    <option value="done">تکمیل شده</option>
                                    <option value="canceled">لغو شده</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label >پیامک ارسالی:</label>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sms" id="smsr0" value="0" >
                                <label class="form-check-label" for="smsr0">
                                    پیامکی ارسال نشود.
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sms" id="smsr1" value="1" >
                                <label class="form-check-label" for="smsr1">
                                    درخواست شما به دلیل بد حسابی رد شد.
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sms" id="smsr2" value="2" >
                                <label class="form-check-label" for="smsr2">
                                    درخواست شما به دلیل داشتن ضمانت رد شد.
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sms" id="smsr3" value="3" >
                                <label class="form-check-label" for="smsr3">
                                    درخواست شما به دلیل داشتن بدهی رد شد.
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sms" id="smsr4" value="4" >
                                <label class="form-check-label" for="smsr4">
                                    با درخواست شما به مبلغ ... ریال موافقت می گردد.
                                </label>
                            </div>
                        </div>

                        <div class="form-group row" id="amount-sms" style="display: none;">
                            <label class="col-lg-2 col-sm-12 col-form-label">مبلغ: </label>
                            <div class="col-lg-10 col-sm-12">
                                <input type="number" class="form-control text-center" name="loan_amount" placeholder="مبلغ وام (ريال)">
                            </div>
                        </div>

                        <div class="form-check">
                            <label class="form-check-label" style="font-weight: bold;">
                                <input type="checkbox" name="reuse" class="form-check-input" value="1">نوبت مجددا قابل استفاده باشد.
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-mdl-frm-btn" data-app_id="" onclick="changeStatus(this)" class="btn btn-primary">ذخیره اطلاعات و ارسال پیامک</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>

        function changeStatus(btn) {
            $(btn).prop("disabled", true);
            let inputs = $('#mdl-form').serializeArray();
            let ajax_data = {
                '_token': "{{ csrf_token() }}",
                'appointment_id': $(btn).data('app_id')
            }
            $.each(inputs, function () {
                ajax_data[this.name] = this.value;
            });
            $.post({
                url: "{{ route('change-status-appointment') }}",
                data: ajax_data,
                success: function (response) {
                    alert(response.message);
                    window.location.reload();
                },
                error: function (response) {
                    alert(response.responseJSON.error);
                    $(btn).prop("disabled", false);
                }
            });
        }

        function putDate (timestamp, result_id) {
            let date = new Date(timestamp).toISOString();
            date = date.split('T');
            $(`#${result_id}`).val(date[0]);
        }

        function changeSort(e) {
            let sortType = $(e).data('sort');
            if (sortType == "asc") {
                sortType = "desc";
            } else {
                sortType = "asc"
            }
            let urlParams = Object.fromEntries(new URL(window.location.href).searchParams.entries());
            urlParams['date-sort'] = sortType;
            window.location.href = window.location.href.split('?')[0] + "?" + $.param(urlParams);
        }

        function editAppointmentModal(button) {
            let appId = $(button).data('app_id');
            $('#submit-mdl-frm-btn').attr('data-app_id', appId);
            $('.cstm-span-mdl').text('');
            $.post({
                url: "{{ route('appointment-summary') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: appId
                },
                success: function (response) {
                    $('#mdl-usr-fullname').text(response.appointment.user.first_name + ' ' + response.appointment.user.last_name);
                    $('#mdl-usr-ntionlcode').text(response.appointment.user.national_code);
                    $('#mdl-usr-mbil').text(response.appointment.user.mobile);
                    let status;
                    switch (response.appointment.status) {
                        case 'pending': status = "معلق"; break;
                        case 'done': status = "تکمیل شده"; break;
                        case 'canceled': status = "لغو شده"; break;
                        case 'rejected': status = "رد شده"; break;
                        case 'checked': status = "بررسی شده"; break;
                    }
                    $('#mdl-apnt-sts').text(status);
                    $('#mdl-apnt-date').text(response.appointment.reserve_date);
                    $('#mdl-apnt-pztn').text(response.appointment.position);
                    if (response.appointment.loan_amount){
                        $('#mdl-apnt-loan-amnt-sec').show();
                        $('#mdl-apnt-loan-amnt').text(response.appointment.loan_amount);
                    } else {
                        $('#mdl-apnt-loan-amnt-sec').hide();
                    }
                    $('#editAppointmentModal').modal('show');

                },
                error: function (response) {
                    console.log(response)
                }
            });

        }

        function formattedTime (time) {
            let newTime = new Date("1970-01-01T" + time + "Z");
            let hours = newTime.getUTCHours();
            let minutes = newTime.getUTCMinutes();
            return hours + ":" + (minutes < 10 ? '0' : '') + minutes;
        }

        $(document).ready(function () {
            $('.convert-to-jalali').each(function () {
                let date_timestamp = $(this).text();
                $(this).text(new persianDate(parseInt(date_timestamp) * 1000).format('dddd D MMMM YYYY'));
            });
            let inputPersianDatepicker = [];
            $.each($('.persian-datepicker'), function () {
                let resultId =$(this).attr('data-name');
                inputPersianDatepicker[resultId] = $(this).persianDatepicker({
                    initialValue: false,
                    format: 'dddd D MMMM YYYY',
                    submitButton: {
                        enabled: false,
                    },
                    minDate: Date.now(),
                    navigator: {
                        enabled: true,
                        scroll: {
                            enabled: false
                        },
                        text: {
                            btnNextText: "»",
                            btnPrevText: "«"
                        }
                    },
                    autoClose: true,
                    onSelect: function(date) {
                        putDate(date, resultId);
                    },
                    position: [$(this).height(), $(this).width()]
                });

                $(this).on('change', function() {
                    if ($(this).val().length == 0) {
                        $('#' + $(this).data('name')).val("");
                    }
                });
            });

            let formatedDate;
            let searchParams = new URLSearchParams(window.location.search);
            if (searchParams.has('from') && searchParams.get('from').length) {
                try {
                    let dateArray = searchParams.get('from').replaceAll('-', '.');
                    let dateUnix = parseInt(Math.floor(new Date(dateArray).getTime()));
                    inputPersianDatepicker['from-inp'].setDate(dateUnix);
                } catch (error) {
                    console.error(error);
                }
            }
            if (searchParams.has('to') && searchParams.get('to').length) {
                try {
                    let dateArray = searchParams.get('to').replaceAll('-', '.');
                    let dateUnix = parseInt(Math.floor(new Date(dateArray).getTime()));
                    inputPersianDatepicker['to-inp'].setDate(dateUnix);
                } catch (error) {
                    console.error(error);
                }
            }

            $(`input[name='sms']`).on('change', function () {
                if ($(this).val() == "4") {
                    $('#amount-sms').show();
                } else {
                    $('#amount-sms').hide();
                }
            });

        });
    </script>
@endsection
