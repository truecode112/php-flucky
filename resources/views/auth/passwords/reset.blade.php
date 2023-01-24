@extends('layouts.app')

@section('page', __('Reset Password'))
@section('title', getSetting('APPLICATION_NAME') . ' | ' . __('Reset Password'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-body auth-section">
                        <form method="POST" action="{{ route('password.update') }}">
                            <div class="row">
                                <div class="col-12 text-center mt-2 mb-3">
                                    <i class="fa fa-envelope change-password-icon"></i>
                                </div>
                            </div>
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group row">
                                <div class="col-md-12 p-0">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" placeholder="{{ __('E-Mail Address') }}"
                                        value="{{ $email ?? old('email') }}" maxlength="50" required autocomplete="email"
                                        autofocus>
                                    <span class="focus-border"></span>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12 p-0">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="{{ __('Password') }}" name="password" maxlength="50" required
                                        autocomplete="new-password">
                                    <span class="focus-border"></span>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12 p-0">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" placeholder="{{ __('Confirm Password') }}"
                                        maxlength="50" required autocomplete="new-password">
                                </div>
                                <span class="focus-border"></span>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-12 p-0">
                                    <button type="submit" class="btn btn-theme">
                                        {{ __('Reset Password') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
