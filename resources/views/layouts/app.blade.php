<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ getSelectedLanguage()->direction }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Styles -->
    <style type="text/css">
        :root {
            --primary-color: {{ getSetting('THEME_COLOR') }};
        }

    </style>
    <script>
        //set the initial theme
        const currentTheme = localStorage.getItem('theme') || "{{ getSetting('DEFAULT_THEME') }}";
        if (currentTheme) document.documentElement.setAttribute('data-theme', currentTheme);
    </script>
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fa.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('storage/images/FAVICON.png') }}">
    @yield('style')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md shadow-sm">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('storage/images/LOGO.png') }}" alt="{{ getSetting('APPLICATION_NAME') }}">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto align-items-center">
                    <li class="nav-item">
                        <span class="nav-link">
                            <div class="theme-switch-wrapper">
                                <button class="dark-theme-setting" title="{{ __('Toggle dark mode') }}">
                                    <i id="themeSwitch" class="fas fa-moon"></i>
                                </button>
                            </div>
                        </span>
                    </li>

                    @if (getLanguages()->count() > 1)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-globe"></i> {{ getSelectedLanguage()->name }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @foreach (getLanguages() as $language)
                                    <a class="dropdown-item @if (getSelectedLanguage()->name == $language->name) active @endif"
                                        href="{{ route('language', ['locale' => $language->code]) }}">{{ $language->name }}</a>
                                @endforeach
                            </div>
                        </li>
                    @endif

                    <!-- Authentication Links -->
                    @guest
                        @if (getSetting('AUTH_MODE') == 'enabled' && getSetting('PAYMENT_MODE') == 'enabled')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('pricing') }}">{{ __('Pricing') }}</a>
                            </li>
                        @endif

                        @if (Route::has('login') && getSetting('AUTH_MODE') == 'enabled')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register') && getSetting('AUTH_MODE') == 'enabled')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}"><button
                                        class="btn btn-theme">{{ __('Join Now') }}</button></a>
                            </li>
                        @endif
                    @else
                        @if (Auth::user()->role == 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin') }}">{{ __('Admin') }}</a>
                            </li>
                        @endif

                        @if (Auth::user()->plan_type == 'free' && getSetting('AUTH_MODE') == 'enabled' && getSetting('PAYMENT_MODE') == 'enabled')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('pricing') }}">
                                    <span class="badge badge-warning p-1">{{ __('Upgrade') }}</span>
                                </a>
                            </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->username }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                @if (getSetting('PAYMENT_MODE') == 'enabled')
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        {{ __('Profile') }}
                                    </a>
                                @endif
                                <a class="dropdown-item" href="{{ route('changePassword') }}">
                                    {{ __('Change Password') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                         document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="app-footer">
            <div class="container-fluid">
                <div class="row d-flex align-items-top">
                    <div class="col-12 col-md-9 text-md-left text-center pad-res">
                        <ul class="footer-links">
                            <li>
                                <a href="{{ route('termsAndConditions') }}"
                                    target="_blank">{{ __('Terms & Conditions') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('privacyPolicy') }}"
                                    target="_blank">{{ __('Privacy Policy') }}</a>
                            </li>
                        </ul>
                        <p>{{ __('Copyright') }} &copy; {{ date('Y') }}
                            {{ getSetting('APPLICATION_NAME') }}. {{ __('All rights reserved') }}</p>
                    </div>
                    <div class="col-12 col-md-3 text-md-right text-center pad-res">
                        <div class="social-data">
                            <p><strong>{{ __('Share with your friends') }}</strong></p>
                            <ul class="social-links">
                                <li>
                                    <a href="" target="_blank" id="fbShare" rel="noreferrer">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="" target="_blank" id="twitterShare" rel="noreferrer">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="" target="_blank" id="waShare" rel="noreferrer">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <div class="cookie">
            <p><i class="fa fa-cookie-bite"></i>
                {{ __('This website uses cookies to ensure you get the best experience on our website') }}
                <a href="{{ route('privacyPolicy') }}"> {{ __('Learn more') }}</a>
            </p>
            <button class="btn btn-theme confirm-cookie">{{ __('Got it') }}</button>
        </div>
    </div>

    <script>
        const socialInvitation = "{{ getSetting('SOCIAL_INVITATION') }}";
        const googleAnalyticsTrackingId = "{{ getSetting('GOOGLE_ANALYTICS_ID') }}";
        const cookieConsent = "{{ getSetting('COOKIE_CONSENT') }}";
        const languages = {
            error_occurred: "{{ __('An error occurred, please try again') }}",
            data_updated: "{{ __('Data updated successfully') }}",
        };
    </script>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @yield('script')
</body>

</html>
