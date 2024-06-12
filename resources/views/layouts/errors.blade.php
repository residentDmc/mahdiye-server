@if(session()->has('error'))
    <script>
        $(document).ready(function () {
            toastr.error(
                "{{ session()->get('error') }}",
                'خطا در عملیات',
                {
                    extendedTimeOut: 500,
                    positionClass: 'toast-top-right',
                    timeOut: 5000,
                    progressBar: true,
                    toastClass: 'toast',
                    closeMethod: true,
                }
            );
        });
    </script>

@elseif($errors->any())
    <script>
        let errors = "";
    </script>
    @foreach($errors->all() as $index => $error)
        @if($index+1 < count($errors->all()))
            <script>
                errors = errors + "{{ $error }}" + `<br>`
            </script>
        @else
            <script>
                errors = errors + "{{ $error }}"
            </script>
        @endif
    @endforeach

    <script>
        $(document).ready(function () {
            toastr.error(
                errors,
                'خطا در عملیات',
                {showDuration: 400,
                    extendedTimeOut: 500,
                    positionClass: 'toast-top-right',
                    timeOut: 5000,
                    progressBar: true,
                    toastClass: 'toast',
                    closeMethod: true,
                })
        });
    </script>
@endif

@if(session()->has('message'))
    <script>
        $(document).ready(function () {
            toastr.success(
                "{{ session()->get('message') }}",
                'عملیات موفقیت آمیز',
                {
                    extendedTimeOut: 500,
                    positionClass: 'toast-top-right',
                    timeOut: 5000,
                    progressBar: true,
                    toastClass: 'toast',
                    closeMethod: true,
                }
            );
        });
    </script>
@endif
