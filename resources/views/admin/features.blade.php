@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            @if (getSetting('PAYMENT_MODE') == 'disabled')
                <span
                    class="badge badge-warning p-2 mb-3">{{ __('The payment mode is disabled, enable now to make the features paid') }}</span>
            @endif
            <table id="features" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Paid') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($features as $key => $value)
                        <tr>
                            <td>{{ $value->id }}</td>
                            <td>{{ $value->title }}</td>
                            <td>{{ $value->description }}</td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input feature-status"
                                        data-id="{{ $value->id }}" id="customSwitch{{ $value->id . 'status' }}"
                                        {{ $value->status == 'active' ? 'checked' : '' }}>
                                    <label class="custom-control-label"
                                        for="customSwitch{{ $value->id . 'status' }}"></label>
                                </div>
                            </td>
                            <td>
                                @if ($value->title == 'FAKE_VIDEO')
                                    <span>-</span>
                                @else
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input feature-paid"
                                            data-id="{{ $value->id }}" id="customSwitch{{ $value->id . 'paid' }}"
                                            {{ $value->paid == 'yes' ? 'checked' : '' }}>
                                        <label class="custom-control-label"
                                            for="customSwitch{{ $value->id . 'paid' }}"></label>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Paid') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
