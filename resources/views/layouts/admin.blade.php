<!doctype html>
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

    <!-- Styles -->
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fa.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('storage/images/FAVICON.png') }}">
    @yield('style')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
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
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin') }}">{{ __('Admin') }}</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">{{ __('Home') }}</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->username }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('changePassword') }}">
                                {{ __('Change Password') }}
                            </a>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                                                                 document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('admin') }}" class="brand-link d-flex justify-content-center">
                <span class="letter-set">{{ getSetting('APPLICATION_NAME')[0] }}</span>
                <img src="{{ asset('storage/images/LOGO.png') }}" alt="{{ getSetting('APPLICATION_NAME') }}">
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('admin') }}" class="nav-link" data-name="admin">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    {{ __('Dashboard') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('users') }}" class="nav-link" data-name="users">
                                <i class="nav-icon fa fa-users"></i>
                                <p>
                                    {{ __('Users') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('reportedUsers') }}" class="nav-link" data-name="reported-users">
                                <i class="nav-icon fa fa-flag"></i>
                                <p>
                                    {{ __('Reported Users') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('bannedUsers') }}" class="nav-link" data-name="banned-users">
                                <i class="nav-icon fa fa-ban"></i>
                                <p>
                                    {{ __('Banned Users') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('income') }}" class="nav-link" data-name="income">
                                <i class="nav-icon fa fa-dollar-sign"></i>
                                <p>
                                    {{ __('Income') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('global-config') }}" class="nav-link" data-name="global-config">
                                <i class="nav-icon fa fa-cog"></i>
                                <p>
                                    {{ __('Global Configuration') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('languages') }}" class="nav-link" data-name="languages">
                                <i class="nav-icon fa fa-language"></i>
                                <p>
                                    {{ __('Languages') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('features') }}" class="nav-link" data-name="features">
                                <i class="nav-icon fa fa-sliders-h"></i>
                                <p>
                                    {{ __('Features') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('signaling') }}" class="nav-link" data-name="signaling">
                                <i class="nav-icon fa fa-signal"></i>
                                <p>
                                    {{ __('Signaling Server') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pages') }}" class="nav-link" data-name="pages">
                                <i class="nav-icon fa fa-file-alt"></i>
                                <p>
                                    {{ __('Pages') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('update') }}" class="nav-link" data-name="update">
                                <i class="nav-icon fa fa-download"></i>
                                <p>
                                    {{ __('Manage Update') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('license') }}" class="nav-link" data-name="license">
                                <i class="nav-icon fa fa-id-badge"></i>
                                <p>
                                    {{ __('Manage License') }}
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('page')</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item active">@yield('page')</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <strong>{{ __('Copyright') }} &copy; {{ date('Y') }}
                {{ getSetting('APPLICATION_NAME') }}.</strong>
            {{ __('All rights reserved') }}
            <div class="float-right d-none d-sm-inline-block">
                <b>{{ __('Version') }}</b> {{ getSetting('VERSION') }}
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script>
        const languages = {
            error_occurred: "{{ __('An error occurred, please try again') }}",
            data_updated: "{{ __('Data updated successfully') }}",
            valid_license: "{{ __('Your license is valid. Type: ') }}",
            invalid_license: "{{ __('Your license is invalid. Error: ') }}",
            confirmation: "{{ __('Are you sure') }}",
            license_uninstalled: "{{ __('License uninstalled') }}",
            license_uninstalled_failed: "{{ __('License uninstallation failed. Error: ') }}",
            update_available: "{{ __('An update is available: Version: ') }}",
            already_latest_version: "{{ __('Application is already at latest version. Version: ') }}",
            application_updated: "{{ __('The application has been successfully updated to the latest version') }}",
            update_failed: "{{ __('Update failed. Error: ') }}",
            user_ignored: "{{ __('The user has been ignored') }}",
            user_banned: "{{ __('The user has been banned') }}",
            user_unbanned: "{{ __('The user has been unbanned') }}",
            select_record: "{{ __('Please select at least one record') }}",
            data_added: "{{ __('Data added successfully') }}",
            data_deleted: "{{ __('The language has been deleted') }}",
            income: "{{ __('Income') }}",
            user_registration: "{{ __('User Registration') }}",
            jan: "{{ __('Jan') }}",
            feb: "{{ __('Feb') }}",
            mar: "{{ __('Mar') }}",
            apr: "{{ __('Apr') }}",
            may: "{{ __('May') }}",
            june: "{{ __('June') }}",
            jul: "{{ __('Jul') }}",
            aug: "{{ __('Aug') }}",
            sep: "{{ __('Sep') }}",
            oct: "{{ __('Oct') }}",
            nov: "{{ __('Nov') }}",
            dec: "{{ __('Dec') }}",
            free: "{{ __('Free') }}",
            paid: "{{ __('Paid') }}",
            male: "{{ __('Male') }}",
            female: "{{ __('Female') }}",
            next: "{{ __('Next') }}",
            previous: "{{ __('Previous') }}",
            search: "{{ __('Search') }}",
            info: "{{ __('Showing page _PAGE_ of _PAGES_') }}",
            lengthMenu: "{{ __('Display _MENU_ records per page') }}",
            zeroRecords: "{{ __('Nothing found - sorry') }}",
            infoEmpty: "{{ __('No records available') }}",
            infoFiltered: "{{ __('filtered from _MAX_ total records') }}",
            next: "{{ __('Next') }}",
            previous: "{{ __('Previous') }}",
            search: "{{ __('Search') }}",
        };
    </script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/common.js') }}"></script>
    @yield('script')
</body>

</html>
