@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="languagesAdd">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Code') }}</label>
                            <input type="text" id="code" placeholder="{{ __('Code') }}" class="form-control"
                                maxlength="64" autofocus required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Name') }}</label>
                            <input type="text" id="name" placeholder="{{ __('Name') }}" class="form-control"
                                maxlength="255" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Direction') }}</label>
                            <select id="direction" class="form-control">
                                <option value="ltr">{{ __('LTR') }}</option>
                                <option value="rtl">{{ __('RTL') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Default') }}</label>
                            <select id="default" class="form-control">
                                <option value="no">{{ __('No') }}</option>
                                <option value="yes">{{ __('Yes') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Status') }}</label>
                            <select id="status" class="form-control">
                                <option value="active">{{ __('Active') }}</option>
                                <option value="inactive">{{ __('Inactive') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('File') }}</label>
                            <input type="file" id="file" class="form-control" accept=".json" required>
                        </div>
                    </div>
                </div>

                <button type="submit" id="save" class="btn btn-primary">{{ __('Save') }}</button>
                <a href="{{ route('languages') }}"><button type="button"
                        class="btn btn-default">{{ __('Back') }}</button></a>
            </form>
        </div>
    </div>
@endsection
