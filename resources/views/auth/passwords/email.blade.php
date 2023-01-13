@extends('layouts.auth')

@section('head_title', $title = trans('passwords.pages.recover.title'))

@section('content')
    <div class="col-xl-7 col-md-9 col-10 d-flex justify-content-center px-0">
        <div class="card bg-authentication rounded-0 mb-0">
            <div class="row m-0">
                <div class="col-lg-6 d-lg-block d-none text-center align-self-center">
                    <img src="{{ asset('assets/theme/images/pages/forgot-password.png') }}" alt="{{ $title }}">
                </div>
                <div class="col-lg-6 col-12 p-0">
                    <div class="card rounded-0 mb-0 px-2 py-1">
                        <div class="card-header pb-1">
                            <div class="card-title">
                                <h4 class="mb-0">{{ $title }}</h4>
                            </div>
                        </div>
                        <p class="px-2 mb-0">{{ trans('passwords.pages.recover.message') }}</p>
                        <div class="card-content">
                            <div class="card-body">
                                @if ($status = session('status'))
                                <div class="alert alert-success">{{ $status }}</div>
                                @endif
                                <form id="form-recover" method="POST" autocomplete="off" role="form" action="{{ route('password.email') }}">
                                    {{ csrf_field() }}
                                    <div class="form-label-group">
                                        <input type="email" name="email" id="inputEmail" class="form-control @if($errors->has('email')){{ 'is-invalid' }}@endif" placeholder="{{ trans('passwords.pages.recover.form.email') }}" autofocus required>
                                        <label for="inputEmail">{{ trans('passwords.pages.recover.form.email') }}</label>
                                        @if($errors->has('email'))
                                        <div class="invalid-feedback">{!! $errors->first('email') !!}</div>
                                        @endif
                                    </div>
                                    <div class="float-md-left d-block mb-1">
                                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-block px-75">{{ trans('passwords.pages.recover.form.back') }}</a>
                                    </div>
                                    <div class="float-md-right d-block mb-1">
                                        <button type="submit" class="btn btn-primary btn-block px-75">{{ trans('passwords.pages.recover.form.recover') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection