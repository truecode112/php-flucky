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
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Username') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Currency') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Gateway') }}</th>
                        <th>{{ __('Transaction ID') }}</th>
                        <th>{{ __('Plan Start Date') }}</th>
                        <th>{{ __('Plan End Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($plans as $key => $value)
                        <tr>
                            <td>{{ $value->id }}</td>
                            <td>{{ $value->username }}</td>
                            <td>{{ $value->amount }}</td>
                            <td>{{ $value->currency }}</td>
                            <td>
                                @if ($value->type == 'monthly')
                                    <span class="badge badge-info">{{ __('Monthly') }}</span>
                                @else
                                    <span class="badge badge-success">{{ __('Yearly') }}</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($value->gateway) }}</td>
                            <td>{{ $value->transaction_id }}</td>
                            <td>{{ $value->plan_start_date }}</td>
                            <td>{{ $value->plan_end_date }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Username') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Currency') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Gateway') }}</th>
                        <th>{{ __('Transaction ID') }}</th>
                        <th>{{ __('Plan Start Date') }}</th>
                        <th>{{ __('Plan End Date') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
