<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'داشبورد')</title>
    <meta name="csrf" content="{{ CSRF_TOKEN() }}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/plugins/ionicons/ionicons.min.css') }}">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/dist/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- Bootstrap 4 RTL -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/plugins/bootstrap/css/bootstrap.min.css') }}">
    <!-- Persian Date picker -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/plugins/persian-date/persian-datepicker.min.css') }}">
    <!-- Custom style for RTL -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/dist/css/custom.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn&display=swap" rel="stylesheet">
    <!-- toaster -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/plugins/toastr/toastr.min.css') }}">
    <style>
        * {
            font-family: 'Vazirmatn', sans-serif;
        }

        .card-title {
            float: right !important;
        }

        #toast-container {
            text-align: right !important;
            direction: rtl !important;
        }
    </style>
    @yield('head')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('layouts.navbar')

    @include('layouts.sidebar')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">@yield('title', 'داشبورد')</h1>
                    </div>
                    <div class="col-sm-6">
                        @yield('breadcrumb')
                        {{--<ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard v1</li>
                        </ol>--}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @yield('content')
                {{--<div class="row"></div>--}}
            </div>
        </section>
    </div>

    <footer class="main-footer text-center">
        پنل مدیریتی رزرو نوبت مهدیه همدان
    </footer>

    <aside class="control-sidebar control-sidebar-dark"></aside>

</div>

<!-- jQuery -->
<script src="{{ asset('assets/dashboard/plugins/jquery/jquery.min.js') }}"></script>

@include('layouts.errors')

<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('assets/dashboard/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 rtl -->
<script src="https://cdn.rtlcss.com/bootstrap/v4.2.1/js/bootstrap.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('assets/dashboard/dist/js/adminlte.js') }}"></script>

<!-- AdminLTE for demo purposes -->
<script src="{{ asset('assets/dashboard/dist/js/demo.js') }}"></script>

<!-- Persian date -->
<script src="{{ asset('assets/dashboard/plugins/persian-date/persian-date.min.js') }}"></script>
<script src="{{ asset('assets/dashboard/plugins/persian-date/persian-datepicker.min.js') }}"></script>

<!-- Ajax form submit -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('assets/dashboard/dist/js/custom.js') }}"></script>
<!-- toaster -->
<script src="{{ asset('assets/dashboard/plugins/toastr/toastr.min.js') }}"></script>

<script>
    let current_datetime = new persianDate().format("dddd, DD MMMM YYYY | H:mm:ss");
    setInterval(function () {
        current_datetime = new persianDate().format("dddd, DD MMMM YYYY | H:mm:ss");
        $('#today-datetime').text(current_datetime);
    },1000);
</script>

@yield('js')
</body>
</html>
