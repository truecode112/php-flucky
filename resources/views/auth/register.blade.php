@extends('layouts.app')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card mb-0 static-content-data">
                    <div class="card-body auth-section">
                        <div class="row">
                            <div class="col-12 text-center mt-2 mb-3">
                                <i class="fa fa-user-plus change-password-icon"></i>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group row">
                                <div class="col-md-12 p-0">
                                    <input id="username" type="text"
                                        class="form-control @error('username') is-invalid @enderror" name="username"
                                        placeholder="{{ __('Username') }}" value="{{ old('username') }}" required
                                        autocomplete="username" maxlength="20" autofocus>
                                    <span class="focus-border"></span>
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12 p-0">
                                    <input id="email" placeholder="{{ __('E-Mail Address') }}" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" maxlength="50" required autocomplete="email">
                                    <span class="focus-border"></span>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12 p-0">
                                    <select id="gender" placeholder="{{ __('Gender') }}"
                                        class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                        <option value="">{{ __('Select') }}</option>
                                        <option value="male" {{ old('gender') == 'mae' ? 'selected' : '' }}>
                                            {{ __('Male') }}</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                            {{ __('Female') }}
                                        </option>
                                    </select>
                                    <span class="focus-border"></span>
                                    @error('gender')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12 p-0">
                                    <input id="password" placeholder="{{ __('Password') }}" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        maxlength="50" required autocomplete="new-password">
                                    <span class="focus-border"></span>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12 p-0">
                                    <input id="password-confirm" placeholder="{{ __('Confirm Password') }}"
                                        type="password" class="form-control" name="password_confirmation" maxlength="50"
                                        required autocomplete="new-password">
                                    <span class="focus-border"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label>
                                    {{ __('By clicking Continue & Start you certify that you are over
                                    :age years old and accept our Terms & Conditions and our Privacy
                                    Policy', ['age' => getSetting('MINIMUM_AGE')]) }}
                                </label>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-12 p-0">
                                    <button type="submit" class="btn btn-theme w-100">
                                        {{ __('Register') }}
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
