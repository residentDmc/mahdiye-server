@extends('layouts.app')

@section('title', $pageTitle = $user->role == "admin" ? 'ویرایش ادمین' : 'ویرایش کاربر')

@section('head')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-search__field {
            border: none !important;
        }
        .select2-selection__choice__display {
            color: black !important;
        }
        .select2-container {
            width: 100% !important;
        }
    </style>
@endsection

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
                    <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm float-right">
                        بازگشت
                        <span class="fa fa-backward"></span>
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST" class="ajax-submit">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-sm-12 col-lg-6">
                                <div class="form-group">
                                    <label for="first_name">نام</label>
                                    <input type="text" class="form-control" id="first_name" value="{{ $user->first_name }}" name="first_name">
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <div class="form-group">
                                    <label for="last_name">نام خانوادگی</label>
                                    <input type="text" class="form-control" id="last_name" value="{{ $user->last_name }}" name="last_name">
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <div class="form-group">
                                    <label for="father_name">نام پدر</label>
                                    <input type="text" class="form-control" id="father_name" value="{{ $user->father_name }}" name="father_name">
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <div class="form-group">
                                    <label for="mobile">موبایل</label>
                                    <input type="number" class="form-control" id="mobile" value="{{ $user->mobile }}" name="mobile">
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <div class="form-group">
                                    <label for="birthdate">تاریخ تولد</label>
                                    <input type="text" class="form-control" id="birthdate" value="{{ $user->birthdate }}">
                                    <input type="text" id="result_birthdate" name="birthdate" value="{{ $user->birthdate }}" style="display: none;">
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <div class="form-group">
                                    <label for="postal_code">کد پستی</label>
                                    <input type="number" class="form-control" id="postal_code" value="{{ $user->postal_code }}" name="postal_code">
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <div class="form-group">
                                    <label for="national_code">کد ملی</label>
                                    <input type="number" class="form-control" id="national_code" value="{{ $user->national_code }}" name="national_code">
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <div class="form-group">
                                    <label for="certificate_number">شماره شناسنامه</label>
                                    <input type="number" class="form-control" id="certificate_number" value="{{ $user->certificate_number }}" name="certificate_number">
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <div class="form-group">
                                    <label for="status">وضعیت</label>
                                    <select name="status" id="status" class="form-control">
                                        <option @selected($user->status == "active") value="active" selected>فعال</option>
                                        <option @selected($user->status == "inactive") value="inactive">غیر فعال</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <div class="form-group">
                                    <label for="role">نقش</label>
                                    <select name="role" id="role" class="form-control">
                                        <option @selected($user->role == "admin") value="admin" selected>مدیر</option>
                                        <option @selected($user->role == "user") value="user">کاربر</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12" id="permissions-section" style="display: {{ $user->role == "admin" ? "" : "none" }}">
                                <div class="form-group">
                                    <label for="permissions">دسترسی های ادمین</label>
                                    <select class="select2 form-control" id="permissions" name="permissions[]" multiple="multiple">
                                        @foreach ($peromissions as $permission)
                                            <option @selected($user->hasPermissionTo($permission['name'])) value="{{ $permission->name }}">{{ $permission->fa_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address">آدرس</label>
                                    <textarea name="address" id="address" cols="30" rows="5" class="form-control">{{ $user->address }}</textarea>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        function putDate (timestamp) {
            let date = new Date(timestamp).toISOString();
            date = date.split('T');
            $('#result_birthdate').val(date[0]);
        }

        $(document).ready(function () {
            putDate(parseInt('{{ $user->birthdate ? \Carbon\Carbon::createFromFormat('Y-m-d', $user->birthdate)->timestamp * 1000 : '0' }}'));
            $('#birthdate').persianDatepicker({
                initialValueType: 'gregorian',
                format: 'D MMMM YYYY',
                submitButton: {
                    enabled: false,
                },
                maxDate: Date.now(),
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
                position: [document.getElementById('birthdate').clientHeight, document.getElementById('birthdate').clientWidth]
            });

            $('.select2').select2({
                closeOnSelect: false,
            });
            $('#role').on('change', function() {
                if ($(this).val() == "admin") {
                    $('#permissions-section').show();
                } else if ($(this).val() == "user") {
                    $('#permissions-section').hide();
                }
            });
        });
    </script>
@endsection
