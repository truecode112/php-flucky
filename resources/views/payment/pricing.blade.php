@extends('layouts.app')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="container-fluid text-center">
        <h1 class="mb-3">{{ __('Choose your plan') }}</h1>
        <div class="container plan-selection">
            <div class="mb-3 ">
                <input type="radio" name="period" value="monthly" id="monthly" checked>
                <label for="monthly" class="btn">{{ __('Monthly') }}</label>
                <input type="radio" name="period" value="yearly" id="yearly">
                <label for="yearly" class="btn">{{ __('Yearly') }}</label>
            </div>
            <div class="card-deck text-center static-content-data">
                <div class="card">
                    <div class="card-header">
                        {{ getSetting('PRICING_PLAN_NAME_FREE') }}
                    </div>
                    <div class="card-body">
                        @if (auth()->user() && auth()->user()->plan_type == 'free')
                            <div class="ribbon-wrapper ribbon-lg">
                                <div class="ribbon bg-primary">
                                    {{ __('Current') }}
                                </div>
                            </div>
                        @endif
                        <h3>{{ __('Free') }}</h3>
                        <ul class="list-unstyled mt-3 mb-4">
                            @foreach ($freeFeatures as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                            <li>{{ __('Report User') }}</li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('register') }}">
                            <button type="button" class="btn btn-secondary" @auth disabled
                                @endauth>{{ __('Join Now') }}</button>
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        {{ getSetting('PRICING_PLAN_NAME_PAID') }}
                    </div>
                    <div class="card-body">
                        @if (auth()->user() && auth()->user()->plan_type == 'paid')
                            <div class="ribbon-wrapper ribbon-lg">
                                <div class="ribbon bg-primary">
                                    {{ __('Current') }}
                                </div>
                            </div>
                        @endif
                        <h3 id="montlyPrice">{{ getCurrencySymbol() . getSetting('MONTHLY_PRICE') }}
                            <small>/{{ __('month') }}</small>
                        </h3>
                        <h3 id="yearlyPrice" hidden>{{ getCurrencySymbol() . getSetting('YEARLY_PRICE') }}
                            <small>/{{ __('year') }}</small>
                        </h3>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>{{ __('All Basic Features') }}</li>
                            @foreach ($paidFeatures as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-footer">
                        <form action="payment">
                            <input type="hidden" id="type" name="type" value="monthly">
                            <button type="submit" class="btn btn-theme"
                                @if (auth()->user() && auth()->user()->plan_type == 'paid') disabled @endif>{{ __('Buy Now') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
