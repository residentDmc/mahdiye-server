{{-- @extends('errors::minimal')

@section('title', "عدم دسترسی")
@section('code', '403')
@section('message', ) --}}

@extends('layouts.app-no-sidebar')

@section('title', 'عدم دسترسی | 403')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                {{-- <div class="card-header">
                    <h3 class="card-title">{{ $pageTitle }}</h3>
                    <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm float-right">
                        بازگشت
                        <span class="fa fa-backward"></span>
                    </a>
                </div> --}}
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 text-center">
                            <p style="margin-top: 15px; font-size:21px">
                                {{ __($exception->getMessage() ?: 'عدم دسترسی') }}
                            </p>
                            <br>
                            <img style="height: 500px" src="{{ asset('assets/dashboard/dist/img/no-access.jpg') }}" alt="NO_ACCESS">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection