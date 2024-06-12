@extends('layouts.app')

@section('head')
    <style>
        a.disabled {
            pointer-events: none;
            cursor: default;
        }
        .custom-list-btn {
            font-size: 10px !important;

        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/dashboard/plugins/sweetalert2/sweetalert2.min.css') }}">

@endsection

@section('title', $pageTitle = 'لیست کاربران')

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
                <div class="card-body">
                    <div class="row">
                        <form action method="GET" class="row col-12 ">
                            <div class="form-group col-lg-3 col-sm-12 pr-3 pl-3">
                                <div class="input-group mb-3">
                                    <input type="text" id="search" name="search" class="form-control" placeholder="کد ملی، موبایل، شماره شناسنامه" value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="form-group col-lg-2 col-sm-12 pr-3 pl-3">
                                <select name="role" id="role" class="form-control">
                                    <option value disabled selected>نقش</option>
                                    <option @selected(request('role') == "all") value="all">همه</option>
                                    <option @selected(request('role') == 'user') value="user" >کاربر</option>
                                    <option @selected(request('role') == 'admin') value="admin" >ادمین</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-2 col-sm-12 pr-3 pl-3">
                                <select name="status" id="status" class="form-control">
                                    <option value disabled selected>وضعیت</option>
                                    <option @selected(request('status') == 'all') value="all" >همه</option>
                                    <option @selected(request('status') == 'active') value="active" >فعال</option>
                                    <option @selected(request('status') == 'inactive') value="inactive" >غیر فعال</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-sm-12 pr-3 pl-3">
                                <input type="text" data-name="created_at" class="form-control persian-datepicker">
                            </div>
                            <div class="form-group col-lg-1 col-sm-12 pr-3 pl-3">
                                <input type="text" id="created_at" name="created_at" hidden>

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
                                <th>نام پدر</th>
                                <th>موبایل</th>
                                <th>کد ملی</th>
                                <th>شماره شناسنامه</th>
                                <th>تاریخ تولد</th>
                                <th>تاریخ ثبت نام</th>
                                <th>وضعیت</th>
                                <th>نقش</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($users) > 0)
                                @foreach($users as $index => $user)
                                    <tr>
                                        <td>{{ ($users->perPage() * (request('page', 1) - 1)) + $index + 1 }}</td>
                                        <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
                                        <td>{{ $user->father_name }}</td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>{{ $user->national_code }}</td>
                                        <td>{{ $user->certificate_number }}</td>
                                        <td class="convert-to-jalali">{{ $user->birthdate ? \Carbon\Carbon::createFromFormat('Y-m-d', $user->birthdate)->timestamp : '' }}</td>
                                        <td class="convert-to-jalali" >{{ $user->created_at->timestamp }}</td>
                                        <td>
                                            @switch($user->status)
                                                @case('active')
                                                    <span class="badge bg-green">فعال</span>
                                                    @break

                                                @case('inactive')
                                                    <span class="badge bg-danger">غیر فعال</span>
                                                    @break

                                                @default
                                                    <span class="badge bg-warning">نامشخص</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch($user->role)
                                                @case('admin')
                                                    <span class="badge bg-green">مدیریت</span>
                                                    @break

                                                @case('user')
                                                    <span class="badge bg-info">کاربر</span>
                                                    @break

                                                @default
                                                    <span class="badge bg-warning">نامشخص</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            <a title="ویرایش" href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary custom-list-btn">
                                                <span class="fa fa-edit"></span>
                                            </a>
                                            <a title="مشاهده" href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-success custom-list-btn">
                                                <span class="fa fa-eye"></span>
                                            </a>
                                            <button title="حذف" data-url="{{ route('users.destroy', $user->id) }}" class="user-destroy-btn btn btn-sm btn-danger custom-list-btn">
                                                <span class="fa fa-trash"></span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="11" class="text-center">کاربری موجود نمی باشد.</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/dashboard/plugins/sweetalert2/new_sweetalert2.min.js') }}"></script>
    <script>
        function destroyUser (url)
        {
            let destroyUserForm =
                `<form action="${url}" method="POST" id="destroy-user-form">
            <input type="hidden" name="_token" value="{{ CSRF_TOKEN() }}">
            <input type="hidden" name="_method" value="DELETE">
            </form>`;
            $('body').append(destroyUserForm);
            $('#destroy-user-form').submit();
        }

        function putDate (timestamp, result_id) {
            let date = new Date(timestamp).toISOString();
            date = date.split('T');
            $(`#${result_id}`).val(date[0]);
        }

        $(document).ready(function () {
            $('.convert-to-jalali').each(function () {
                let date_timestamp = $(this).text();
                if ($.trim(date_timestamp).length >= 2)
                    $(this).text(new persianDate(parseInt(date_timestamp) * 1000).format('YYYY/M/D'));
            });

            $('.user-destroy-btn').on('click', function () {
                let url = $(this).attr('data-url');
                Swal.fire({
                    title: "آیا از حذف کاربر اطمینان دارید؟",
                    text: "توجه داشته باشید در صورت حذف کاربر، سوابق مرتبط با کاربر نیز حذف خواهند شد.",
                    showCancelButton: true,
                    confirmButtonText: "حذف",
                    confirmButtonColor: "#d33",
                    cancelButtonColor: '#0069d9',
                    cancelButtonText: "انصراف",
                }).then((result) => {
                    if (result.isConfirmed) {
                        destroyUser(url);
                    }
                });
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

            let searchParams = new URLSearchParams(window.location.search);
            if (searchParams.has('created_at') && searchParams.get('created_at').length) {
                try {
                    let dateArray = searchParams.get('created_at').replaceAll('-', '.');
                    let dateUnix = parseInt(Math.floor(new Date(dateArray).getTime()));
                    inputPersianDatepicker['created_at'].setDate(dateUnix);
                } catch (error) {

                }
            }
        });
    </script>
@endsection
