@extends('layouts.app')

@section('title', $pageTitle = 'مدیریت نوبت ها')

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
                    <h3 class="card-title">رزرو جدید (گروهی)</h3>
                    <a href="{{ route('reserve.index') }}" class="btn btn-primary btn-sm float-right">
                        بازگشت
                        <span class="fa fa-backward"></span>
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-sm-12 col-lg-6">
                            <label for="start-date">از تاریخ:</label>
                            <input type="text" class="form-control date-picker" id="start-date">
                            <input type="hidden" name="s_date" id="start_date">
                        </div>
                        <div class="form-group col-sm-12 col-lg-6">
                            <label for="end-date">تا تاریخ:</label>
                            <input type="text" class="form-control date-picker" id="end-date">
                            <input type="hidden" name="e_date" id="end_date">
                        </div>
                    </div>
                    <hr>
                    <h3 class="text-center">لیست رزرو ها</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">زمان شروع</th>
                                <th class="text-center">زمان پایان</th>
                                <th class="text-center">ظرفیت</th>
                                <th class="text-center">وضعیت</th>
                                <th style="width: 15px;" class="text-center">حذف</th>
                            </tr>
                        </thead>
                        <tbody id="tbody"></tbody>
                    </table>
                    <div class="form-group mt-2">
                        <button id="add-record" class="btn btn-block btn-secondary">افزودن ردیف جدید</button>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="callout callout-success" style="background-color: #9fcba0;">
                                <h5><i class="icon fas fa-check"></i> توجه</h5>
                                آیتم های <b>از تاریخ</b> و <b>تا تاریخ</b> نشان دهنده بازه زمانی مورد نظر شما برای ایجاد مجموعه ای از تاریخ های رزرو می باشد.
                                <br>
                                جدول <b>لیست رزرو ها</b> نیز شامل بازه های زمانی موزد نظر شما هستند. این بازه های زمانی در تمام تاریخ های انتخاب شده ایجاد می گردند.
                                <br>
                                در صورت تایید میتوانید با کلیک روی دکمه ثبت تغییرات، نسبت به ذخیره سازی اطلاعات اقدام نمایید.
                                <br>
                            </div>

                            <div class="callout callout-danger" style="background-color: #ff8e8e;">
                                <h5><i class="icon fas fa-exclamation-triangle"></i> توجه</h5>
                                لطفا پیش از ثبت تغییرات از صحیح بودن تاریخ های آغاز و پایان و همچنین بازه های زمانی اطمینان حاصل فرمایید.
                                <br>
                                در صورتیکه بازه های زمانی به نادرستی ایجاد شوند، می بایست بصورت تکی حذف شوند. 
                            </div>

                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary mt-3 btn-block" id="save-changes">ثبت تغییرات</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var
        persianNumbers = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g],
        arabicNumbers  = [/٠/g, /١/g, /٢/g, /٣/g, /٤/g, /٥/g, /٦/g, /٧/g, /٨/g, /٩/g],
        fixNumbers = function (str)
        {
            if(typeof str === 'string')
            {
                for(var i=0; i<10; i++)
                {
                str = str.replace(persianNumbers[i], i).replace(arabicNumbers[i], i);
                }
            }
            return str;
        };

        function reloadTimeEl() {
            $.each($('.time_picker_only'), function () {
                $(this).persianDatepicker({
                    initialValue: false,
                    onlyTimePicker: true,
                    format: 'H:m',
                    navigator: {
                        enabled: true,
                        scroll: {
                            enabled: false
                        }
                    },
                    timePicker: {
                        enabled: true,
                        step: 1,
                        hour: {
                            enabled: true,
                        },
                        minute: {
                            enabled: true,
                        },
                        second: {
                            enabled: false,
                        }
                    },
                    submitButton: {
                        enabled: false,
                    },
                    autoClose: true,
                    pickerPosition: "top-right",
                    position: [$(this)[0].clientHeight, $(this)[0].clientWidth]
                });
            });
        }

        function deleteParentTr(id) {
            $(document).find(`#${id}`).remove();
        }

        function putDate (timestamp, result_id) {
            let date = new Date(timestamp).toISOString();
            date = date.split('T');
            $(`#${result_id}`).val(date[0]);
        }

        $(document).ready(function () {

            $('#add-record').click(function() {
                let tr_id = Date.now();
                let tr = 
                    `<tr id="${tr_id}" class="time-record-tr">
                        <td style="width: 22.5%">
                            <div class="form-group">
                                <input type="text" class="form-control time_picker_only start_time text-center" title="زمان شروع">
                            </div>
                        </td>
                        <td style="width: 22.5%">
                            <div class="form-group">
                                <input type="text" class="form-control time_picker_only end_time text-center" title="زمان پایان">
                            </div>
                        </td>
                        <td style="width: 22.5%">
                            <div class="form-group">
                                <input type="number" class="form-control capacity text-center" title="ظرفیت">
                            </div>
                        </td>
                        <td style="width: 22.5%">
                            <select class="form-control status text-center" title="وضعیت">
                                <option selected disabled > وضعیت </option>
                                <option value="active">فعال</option>
                                <option value="inactive">غیر فعال</option>
                            </select>
                        </td>
                        <td style="width: 10%">
                            <button onclick="deleteParentTr('${tr_id}')" class="btn btn-danger btn-sm"> حذف <i class="fa fa-trash"></i></button>
                        </td>
                    </tr>`;
                let last_record = $("#add-record-tr");
                $(tr).appendTo($('#tbody'));
                reloadTimeEl();
            });


            $('#start-date').persianDatepicker({
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
                    putDate(date, 'start_date');
                },
                position: [document.getElementById('start-date').clientHeight, document.getElementById('start-date').clientWidth]
            });
            $('#end-date').persianDatepicker({
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
                    putDate(date, 'end_date');
                },
                position: [document.getElementById('end-date').clientHeight, document.getElementById('end-date').clientWidth]
            });

            $('#save-changes').click(function() {
                let form_data = {"start_date": null, "end_date": null, "time_records": null};
                form_data.start_date = $('#start_date').val();
                form_data.end_date = $('#end_date').val();

                let time_records = [];
                $.each($(document).find('.time-record-tr'), function () {
                    let form_controls_els = $(this).find('.form-control');
                    $.each(form_controls_els, function () {
                        let input_title = $(this).attr('title');
                        if ($(this).val() == null || fixNumbers($(this).val()).length == 0 || fixNumbers($(this).val()) == null) {
                            alert('مقدار وارد شده برای ' + input_title + ' صحیح نمی باشد.');
                            return false;
                        }
                    });

                    time_records.push({
                        'start_time': fixNumbers($(form_controls_els[0]).val()),
                        'end_time': fixNumbers($(form_controls_els[1]).val()),
                        'capacity': fixNumbers($(form_controls_els[2]).val()),
                        'status': fixNumbers($(form_controls_els[3]).val()),
                    });
                });
                form_data.time_records = time_records;
                $.post({
                    url: "{{ route('reserve.store-multiple') }}",
                    type: "POST",
                    data: {
                        _token: $(`meta[name='csrf']`).attr('content'),
                        fields: form_data,
                    },
                    success: function (response) {
                        window.location.href = "{{ route('reserve.index') }}"
                    },
                    error: function (response) {
                        alert('خطا در ذخیره اظلاعات لطفا با پشتیبانی تماس بگیرید.')
                    }
                });
            });
        });
    </script>
@endsection
