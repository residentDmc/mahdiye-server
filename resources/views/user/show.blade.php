@extends('layouts.app')

@section('title', $pageTitle = 'مشاهده کاربر')

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
                    <div class="row">
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group">
                                <label for="first_name">نام</label>
                                <input type="text" class="form-control" id="first_name" disabled value="{{ $user->first_name }}" name="first_name">
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group">
                                <label for="last_name">نام خانوادگی</label>
                                <input type="text" class="form-control" id="last_name" disabled value="{{ $user->last_name }}" name="last_name">
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group">
                                <label for="father_name">نام پدر</label>
                                <input type="text" class="form-control" id="father_name" disabled value="{{ $user->father_name }}" name="father_name">
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group">
                                <label for="mobile">موبایل</label>
                                <input type="number" class="form-control" id="mobile" disabled value="{{ $user->mobile }}" name="mobile">
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group">
                                <label for="birthdate">تاریخ تولد</label>
                                <input type="text" class="form-control" id="birthdate" disabled value="{{ $user->birthdate }}">
                                <input type="text" id="result_birthdate" name="birthdate" disabled value="{{ $user->birthdate }}" style="display: none;">
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group">
                                <label for="postal_code">کد پستی</label>
                                <input type="number" class="form-control" id="postal_code" disabled value="{{ $user->postal_code }}" name="postal_code">
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group">
                                <label for="national_code">کد ملی</label>
                                <input type="number" class="form-control" id="national_code" disabled value="{{ $user->national_code }}" name="national_code">
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group">
                                <label for="certificate_number">شماره شناسنامه</label>
                                <input type="number" class="form-control" id="certificate_number" disabled value="{{ $user->certificate_number }}" name="certificate_number">
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group">
                                <label for="status">وضعیت</label>
                                <select disabled name="status" id="status" class="form-control">
                                    <option selected value="active">
                                        @if($user->status == "active") فعال @elseif($user->status == "inactive") غیر فعال @endif
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group">
                                <label for="role">نقش</label>
                                <select disabled  name="role" id="role" class="form-control">
                                    <option @selected($user->role == "admin") value="admin" selected>مدیر</option>
                                    <option @selected($user->role == "user") value="user">کاربر</option>
                                </select>
                            </div>
                        </div>
                        @if ($user->role == "admin")
                        <div class="col-12">
                            <div class="form-group">
                                <label for="address">لیست دسترسی های ادمین </label>
                                <textarea disabled  name="address" id="address" cols="30" rows="5" class="form-control">
@foreach ($user->permissions as $permission)
 - {{ $permission->fa_name }}
@endforeach
                                </textarea>
                            </div>
                        </div>
                        @endif
                        <div class="col-12">
                            <div class="form-group">
                                <label for="address">آدرس</label>
                                <textarea disabled  name="address" id="address" cols="30" rows="5" class="form-control">{{ $user->address }}</textarea>
                            </div>
                        </div>
                    </div>
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
        });
    </script>
@endsection
