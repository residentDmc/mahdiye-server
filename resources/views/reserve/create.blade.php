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
                    <h3 class="card-title">رزرو جدید</h3>
                    <a href="{{ route('reserve.index') }}" class="btn btn-primary btn-sm float-right">
                        بازگشت
                        <span class="fa fa-backward"></span>
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('reserve.store') }}" method="POST" class="ajax-submit">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12 col-lg-6">
                                <div class="form-group">
                                    <label for="date">تاریخ</label>
                                    <input type="text" class="form-control" id="date">
                                    <input type="text" id="result_date" name="date" style="display: none;">
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_time">زمان شروع</label>
                                    <input type="text" class="form-control time_picker_only" id="s_time" name="start_time">
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-3">
                                <div class="form-group">
                                    <label for="e_time">زمان پایان</label>
                                    <input type="text" class="form-control time_picker_only" id="e_time" name="end_time">
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <div class="form-group">
                                    <label for="capacity">ظرفیت</label>
                                    <input type="number" class="form-control" id="capacity" name="capacity">
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <div class="form-group">
                                    <label for="status">وضعیت</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="active" selected>فعال</option>
                                        <option value="inactive">غیر فعال</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-12">
                                <div class="form-group text-center">
                                    <button class="btn btn-success w-50">ذخیره</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function putDate (timestamp) {
            let date = new Date(timestamp).toISOString();
            date = date.split('T');
            $('#result_date').val(date[0]);
        }

        $(document).ready(function () {
            putDate(Date.now());
            $('#date').persianDatepicker({
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
                onSelect: function(dateText) {
                    putDate(dateText);
                },
                position: [document.getElementById('date').clientHeight, document.getElementById('date').clientWidth]
            });

            $.each($('.time_picker_only'), function () {
                let position = [document.getElementById($(this).attr('id')).clientHeight, document.getElementById($(this).attr('id')).clientWidth];
                $(this).persianDatepicker({
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
                    position: position
                });
            });

        });



    </script>
@endsection
