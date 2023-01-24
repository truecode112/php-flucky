@extends('layouts.app')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="container">
        <h3 class="mb-3">{{ __('Hi') . ', ' . auth()->user()->username }}!</h3>

        @if (Session::has('success'))
            <div class="alert alert-success text-center" role="alert">
                {{ Session::get('success') }}
            </div>
        @endif

        <p>{{ __('Your gender is set to') }} <span class="badge badge-info">{{ ucfirst(auth()->user()->gender) }}</span>
        </p>

        @if (auth()->user()->plan_status == 'active')
            <p>{{ __('Your plan is') }} <span class="badge badge-success">{{ __('Active') }}</span>
                {{ __('and is valid till') }}
                {{ !$userPlan->isEmpty() ? $userPlan[0]->plan_end_date : '-' }}.</p>
        @elseif(auth()->user()->plan_status == 'inactive')
            <p>{{ __('Your plan is') }} <span class="badge badge-info">{{ __('Inactive') }}</span>, <a
                    href="{{ route('pricing') }}">{{ __('Upgrade now') }}</a>!</p>
        @else
            <p>{{ __('Your plan is') }} <span class="badge badge-danger">{{ __('Expired') }}</span>, <a
                    href="{{ route('pricing') }}">{{ __('Upgrade now') }}</a>!</p>
        @endif

        @if (!$userPlan->isEmpty())
            <div class="table-responsive mb-3">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ __('Amount') }}</th>
                            <th scope="col">{{ __('Type') }}</th>
                            <th scope="col">{{ __('Currency') }}</th>
                            <th scope="col">{{ __('Gateway') }}</th>
                            <th scope="col">{{ __('Plan Start Date') }}</th>
                            <th scope="col">{{ __('Plan End Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($userPlan as $key => $value)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ $value->amount }}</td>
                                <td>{{ ucfirst($value->type) }}</td>
                                <td>{{ $value->currency }}</td>
                                <td>{{ ucfirst($value->gateway) }}</td>
                                <td>{{ $value->plan_start_date }}</td>
                                <td>{{ $value->plan_end_date }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>{{ __('Your transactions will be displayed here') }}</p>
        @endif
    </div>
@endsection
