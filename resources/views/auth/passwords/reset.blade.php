@extends('layouts.auth')

@section('head_title', $title = trans('passwords.pages.reset.title'))

@section('content')
    <div class="col-xl-7 col-10 d-flex justify-content-center">
        <div class="card bg-authentication rounded-0 mb-0 w-100">
            <div class="row m-0">
                <div class="col-lg-6 d-lg-block d-none text-center align-self-center p-0">
                    <img src="{{ asset('assets/theme/images/pages/reset-password.png') }}" alt="{{ $title }}">
                </div>
                <div class="col-lg-6 col-12 p-0">
                    <div class="card rounded-0 mb-0 px-2">
                        <div class="card-header pb-1">
                            <div class="card-title">
                                <h4 class="mb-0">{{ $title }}</h4>
                            </div>
                        </div>
                        <p class="px-2">{{ trans('passwords.pages.reset.message') }}</p>
                        <div class="card-content">
                            <div class="card-body pt-1">
                                <form id="form-reset" method="POST" autocomplete="off" role="form" action="{{ route('password.request') }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <fieldset class="form-label-group">
                                        <input type="email" name="email" class="form-control @if($errors->has('email')){{ 'is-invalid' }}@endif" id="user-email" placeholder="{{ trans('passwords.pages.reset.form.email') }}" value="{{ $email }}" required readonly>
                                        <label for="user-email">{{ trans('passwords.pages.reset.form.email') }}</label>
                                        @if($errors->has('email'))
                                        <div class="invalid-feedback">{!! $errors->first('email') !!}</div>
                                        @endif
                                    </fieldset>
                                    <fieldset class="form-label-group">
                                        <input type="password" name="password" class="form-control @if($errors->has('password')){{ 'is-invalid' }}@endif" id="user-password" placeholder="{{ trans('passwords.pages.reset.form.password') }}" required>
                                        <label for="user-password">{{ trans('passwords.pages.reset.form.password') }}</label>
                                        @if($errors->has('password'))
                                        <div class="invalid-feedback">{!! $errors->first('password') !!}</div>
                                        @endif
                                    </fieldset>
                                    <fieldset class="form-label-group">
                                        <input type="password" name="password_confirmation" class="form-control" id="user-confirm-password" placeholder="{{ trans('passwords.pages.reset.form.confirm_password') }}" required>
                                        <label for="user-confirm-password">{{ trans('passwords.pages.reset.form.confirm_password') }}</label>
                                    </fieldset>
                                    <div class="row pt-2">
                                        <div class="col-12 col-md-6 mb-1">
                                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-block px-0">{{ trans('passwords.pages.reset.form.back') }}</a>
                                        </div>
                                        <div class="col-12 col-md-6 mb-1">
                                            <button type="submit" class="btn btn-primary btn-block px-0">{{ trans('passwords.pages.reset.form.reset') }}</button>
                                        </div>
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
