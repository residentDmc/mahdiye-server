@extends('layouts.app')

@section('head')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection

@section('title', $pageTitle = 'شرایط و قوانین')

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
                </div>
                <div class="card-body">
                    <form action="{{ url('dashboard/settings/privacy-policy/edit') }}" method="POST" class="ajax-submit">
                        @csrf
                        <div class="row">
                            <div style="width: 100% !important;">
                                <textarea name="editor" id="editor" style="width: 100% !important;">{!! $setting->value !!}</textarea>
                            </div>
                            <div class="col-sm-12 col-lg-12 mt-4">
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
    <script src="https://cdn.tiny.cloud/1/3a6rg8ay77jx5inzybl3xl5ux6nn3m566z3azs9qubtb1fdq/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tinymce/tinymce-jquery@2/dist/tinymce-jquery.min.js"></script>
    <script>
        $('textarea#editor').tinymce({
            height: 500,
            directionality : 'rtl',
            language: 'fa',
            resize: false,
            width: '100%',
        });
    </script>
@endsection
