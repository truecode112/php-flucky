@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Username') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Gender') }}</th>
                        <th>{{ __('Plan Type') }}</th>
                        <th>{{ __('Plan Status') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Created Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $value)
                        <tr>
                            <td>{{ $value->id }}</td>
                            <td>{{ $value->username }}</td>
                            <td>{{ $value->email }}</td>
                            <td>
                                @if ($value->gender == 'male')
                                    <span class="badge badge-success">{{ __('Male') }}</span>
                                @else
                                    <span class="badge badge-primary">{{ __('Female') }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($value->plan_type == 'free')
                                    <span class="badge badge-info">{{ __('Free') }}</span>
                                @else
                                    <span class="badge badge-success">{{ __('Paid') }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($value->plan_status == 'active')
                                    <span class="badge badge-success">{{ __('Active') }}</span>
                                @elseif($value->plan_status == 'inactive')
                                    <span class="badge badge-info">{{ __('Inactive') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('Expired') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input user-status"
                                        data-id="{{ $value->id }}" id="customSwitch{{ $value->id }}"
                                        {{ $value->status == 'active' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="customSwitch{{ $value->id }}"></label>
                                </div>
                            </td>
                            <td>{{ $value->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Username') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Gender') }}</th>
                        <th>{{ __('Plan Type') }}</th>
                        <th>{{ __('Plan Status') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Created Date') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
