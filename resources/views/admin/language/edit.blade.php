@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="">
                <label>{{ $model->name . ' (' . $model->code . ')' }}</label>
                <a href="{{ '/languages/download-file/' . $model->code }}" class="float-right"><button
                        class="btn btn-warning btn-sm ml-1"
                        title="{{ __('Download File') }}">{{ __('Download File') }}</button></a>
            </div>
            <hr>

            <form id="languagesEdit">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Direction') }}</label>
                            <select id="direction" class="form-control">
                                <option value="ltr" @if ($model->direction == 'ltr') selected @endif>{{ __('LTR') }}</option>
                                <option value="rtl" @if ($model->direction == 'rtl') selected @endif>{{ __('RTL') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Default') }}</label>
                            <select id="default" class="form-control">
                                <option value="no" @if ($model->default == 'no') selected @endif>{{ __('No') }}</option>
                                <option value="yes" @if ($model->default == 'yes') selected @endif>{{ __('Yes') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Status') }}</label>
                            <select id="status" class="form-control">
                                <option value="active" @if ($model->status == 'active') selected @endif>{{ __('Active') }}</option>
                                <option value="inactive" @if ($model->status == 'inactive') selected @endif>{{ __('Inactive') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('File') }}</label>
                            <input type="file" id="file" class="form-control" accept=".json">
                        </div>
                    </div>
                </div>

                <button type="submit" id="save" class="btn btn-primary">{{ __('Save') }}</button>
                <a href="{{ route('languages') }}"><button type="button"
                        class="btn btn-default">{{ __('Back') }}</button></a>
                <input type="hidden" id="id" value="{{ $model->id }}">
            </form>
        </div>
    </div>
@endsection
