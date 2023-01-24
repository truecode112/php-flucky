@extends('layouts.app')

@section('page', __('Reset Password'))
@section('title', getSetting('APPLICATION_NAME') . ' | ' . __('Reset Password'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-body auth-section">
                        <div class="row">
                            <div class="col-12 text-center mt-2 mb-3">
                                <i class="fa fa-envelope change-password-icon"></i>
                            </div>
                        </div>
                        @if (session('status'))
                            <div class="row form-group mb-0">
                                <div class="col-12 p-0 mt-2">
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="form-group row">
                                <div class="col-md-12 p-0">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}" maxlength="50"
                                        required autocomplete="email" autofocus>
                                    <span class="focus-border"></span>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-12 p-0 mt-2 mb-3">
                                    <button type="submit" class="btn btn-theme w-100">
                                        {{ __('Send Password Reset Link') }}
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
