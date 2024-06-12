@extends('layouts.app')

@section('head')
    <style>
        a.disabled {
            pointer-events: none;
            cursor: default;
        }
    </style>
@endsection

@section('title', $pageTitle = 'مدیریت رزرو ها')

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
                    <h3 class="card-title">لیست رزرو ها</h3>
                    <a href="{{ route('reserve.create') }}" class="btn btn-outline-primary btn-sm float-right mr-2">
                        افزودن تکی
                        <span class="fa fa-plus"></span>
                    </a>

                    <a href="{{ route('reserve.create-multiple') }}" class="btn btn-primary btn-sm float-right mr-2">
                        افزودن گروهی
                        <span class="fa fa-plus"></span>
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['get-report' => 1]) }}" class="btn btn-primary btn-sm float-right">
                        خروجی اکسل
                        <span class="fa fa-print"></span>
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="filter-table">فیلتر جدول</label>
                                <select name="filter-table" id="filter-table" class="form-control">
                                    <option value="active" @selected(request('filter') == null || request('filter') == "active") >فعال</option>
                                    <option value="inactive" @selected(request('filter') == null || request('filter') == "inactive") >غیر فعال</option>
                                    <option value="expired" @selected(request('filter') == null || request('filter') == "expired") >منقضی شده</option>
                                    <option value="today" @selected(request('filter') == null || request('filter') == "today") >متعلق به امروز</option>
                                    <option value="all" @selected(request('filter') == null || request('filter') == "all") >همه</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover table-condensed small text-center">
                        <thead>
                        <tr>
                            <th>ردیف</th>
                            <th>روز</th>
                            <th>ساعت</th>
                            <th>ظرفیت کل / نوبت ها</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($reserves) > 0)
                            @foreach($reserves as $index => $reserve)
                                @php if ($reserve->status == "expired") { $expired = true; } else { $expired = false; } @endphp
                                <tr @if($expired) style="background-color: #ff9191" @endif>
                                    <td>{{ ($reserves->perPage() * (request('page', 1) - 1)) + $index + 1 }}</td>
                                    <td class="convert-to-jalali">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $reserve->date)->timestamp }}</td>
                                    <td>
                                        {{ "شروع: " . \Carbon\Carbon::createFromFormat('H:i:s', $reserve->start_time)->format('H:i') }}
                                        <br>
                                        {{ "پایان: " . \Carbon\Carbon::createFromFormat('H:i:s', $reserve->end_time)->format('H:i') }}
                                    </td>
                                    <td>
                                        {{ $reserve->capacity . " / " . $reserve->used }}
                                        @php
                                            $percent = ($reserve->used / $reserve->capacity) * 100;
                                            if ($percent == 100) {
                                                $color = "#ff6767";
                                            } elseif ($percent >= 75 && $percent < 100) {
                                                $color = "#fbcf51";
                                            } elseif ($percent < 75) {
                                                $color = "#52c95b";
                                            }
                                        @endphp
                                        <div class="progress" style="background-color: #c1c1c1 !important;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%; background-color: {{ $color }}"></div>
                                        </div>
                                    </td>
                                    <td>{{ $reserve->status_fa }}</td>
                                    <td>
                                        <form style="display: inline" action="{{ route('reserve.destroy', $reserve->id) }}" class="ajax-submit show-approve" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button {{ $expired ? 'disabled' : '' }} type="submit" class="btn btn-danger delete-reserve btn-sm m-1">
                                                حذف
                                                <span class="fa fa-trash"></span>
                                            </button>
                                        </form>
                                        <a style="display: inline" href="{{ route('reserve.edit', $reserve->id) }}" class="btn btn-sm btn-primary m-1 {{ $expired ? 'disabled' : '' }}">
                                            ویرایش
                                            <span class="fa fa-edit"></span>
                                        </a>
                                        <a style="display: inline" href="{{ route('reserve.show', $reserve->id) }}" class="btn btn-sm btn-success m-1">
                                            نوبت ها
                                            <span class="fa fa-users"></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">رزروی موجود نمی باشد.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>

                    {{ $reserves->links() }}
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('.convert-to-jalali').each(function () {
                let date_timestamp = $(this).text();
                $(this).text(new persianDate(parseInt(date_timestamp) * 1000).format('dddd D MMMM YYYY'));
            });

            $('#filter-table').change(function () {
                let val = $(this).val();
                let base_url = new URL("{{ route('reserve.index') }}");
                base_url.searchParams.set('filter', val);
                window.location.href = base_url.href;
            });
        });
    </script>
@endsection
