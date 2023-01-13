@extends('layouts.auth')

@section('head_title', $title = trans('auth.pages.login.title'))

@section('content')
    <div class="col-xl-8 col-11 d-flex justify-content-center">
        <div class="card bg-authentication rounded-0 mb-0">
            <div class="row m-0">
                <div class="col-lg-6 d-lg-block d-none text-center align-self-center px-1 py-0">
                    <img src="{{ asset('assets/theme/images/pages/login.png') }}" alt="{{ $title }}">
                </div>
                <div class="col-lg-6 col-12 p-0">
                    <div class="card rounded-0 mb-0 px-2">
                        <div class="card-header pb-1">
                            <div class="card-title">
                                <h4 class="mb-0">{{ $title }}</h4>
                            </div>
                        </div>
                        <p class="px-2">{{ trans('auth.pages.login.message') }}</p>
                        <div class="card-content">
                            <div class="card-body pt-1">
                                <form id="form-login" method="POST" autocomplete="off" class="p-t-15" role="form" action="{{ route('login') }}">
                                    {{ csrf_field() }}
                                    <fieldset class="form-label-group form-group position-relative has-icon-left">
                                        <input type="email" name="email" class="form-control @if($errors->has('email')){{ 'is-invalid' }}@endif" id="user-name" placeholder="{{ trans('auth.pages.login.form.username') }}" required autofocus>
                                        <div class="form-control-position">
                                            <i class="feather icon-user"></i>
                                        </div>
                                        <label for="user-name">{{ trans('auth.pages.login.form.username') }}</label>
                                        @if($errors->has('email'))
                                        <div class="invalid-feedback">{!! $errors->first('email') !!}</div>
                                        @endif
                                    </fieldset>
                                    <fieldset class="form-label-group position-relative has-icon-left">
                                        <input type="password" name="password" class="form-control" id="user-password" placeholder="{{ trans('auth.pages.login.form.password') }}" required>
                                        <div class="form-control-position">
                                            <i class="feather icon-lock"></i>
                                        </div>
                                        <label for="user-password">{{ trans('auth.pages.login.form.password') }}</label>
                                    </fieldset>
                                    <div class="form-group d-flex justify-content-between align-items-center">
                                        <div class="text-left">
                                            <fieldset class="checkbox">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input type="checkbox" name="remember" checked>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">{{ trans('auth.pages.login.form.remember_me') }}</span>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="text-right">
                                            <a href="{{ route('password.request') }}" class="card-link">{{ trans('auth.pages.login.form.forgot_password') }}</a>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary float-right btn-inline">{{ trans('auth.pages.login.form.login') }}</button>
                                </form>
                            </div>
                        </div>
                        <div class="login-footer">&nbsp;</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection