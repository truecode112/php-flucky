@extends('layouts.app')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-6 pl-4 pr-4">
                <div class="card">
                    <div class="card-body auth-section">
                        <form id="changePasswordEdit">
                            <div class="row">
                                <div class="col-12 text-center mt-2 mb-3">
                                    <i class="fa fa-lock change-password-icon"></i>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 p-0">
                                    <input type="password" name="current" class="form-control"
                                        placeholder="{{ __('Enter Current Password') }}" maxlength="50" required>
                                    <span class="focus-border"></span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-12 p-0">
                                    <input type="password" name="new" class="form-control"
                                        placeholder="{{ __('Enter New Password') }}" maxlength="50" required>
                                    <span class="focus-border"></span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-12 p-0">
                                    <input type="password" name="confirm" class="form-control"
                                        placeholder="{{ __('Confirm New Password') }}" maxlength="50" required>
                                    <span class="focus-border"></span>
                                </div>
                            </div>
                            <div class="form-group row mt-4">
                                <div class="col-md-12 p-0">
                                    <button type="submit" id="save" class="btn btn-theme w-100">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
