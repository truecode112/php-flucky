@extends('layouts.dashboard')

@section('title', __('Meta title'))
@section('description', __('Meta description'))
@section('keywords', __('Meta keywords'))

@section('style')
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <section class="dashboard">

        <div id="permission"></div>
        <canvas id="canvas" class="hide"></canvas>

        <!-- video section :: start -->
        <div class="container-fluid main video-dask" id="video-section">
            <div class="row">
                @if (getFeature('VIDEO_CHAT', 'status') == 'active')
                    <div class="col-12 col-md-5 col-lg-5 col-xl-3 video-section pr-0">
                        <div class="remote-video-container">
                            <div class="remote-user-info hide">
                                <img id="partnerCountryVideo" src="" alt="{{ __('Country Flag') }}" width="25" />
                                <span id="partnerName"></span>
                            </div>
                            <button class="action-video report hide" data-toggle="tooltip" data-placement="top"
                                title="{{ __('Report') }}"><i class="fa fa-flag"></i></button>
                            <video id="remoteVideo" autoplay playsinline></video>
                            <i class="fa fa-video video-load-icon"></i>
                        </div>
                        <div class="local-video-container">
                            <video id="localVideo" muted autoplay playsinline></video>
                            <i class="fa fa-video video-load-icon"></i>
                            <div class="video-actions">
                                <button class="action-video video-off" data-toggle="tooltip" data-placement="top"
                                    title="{{ __('Camera Off') }}"><i class="fa fa-video"></i></button>
                                <button class="action-video hide video-on" data-toggle="tooltip" data-placement="top"
                                    title="{{ __('Camera On') }}"><i class="fa fa-video-slash"></i></button>
                                <button class="action-video audio-mute" data-toggle="tooltip" data-placement="top"
                                    title="{{ __('Mute Audio') }}"><i class="fa fa-microphone"></i></button>
                                <button class="action-video hide audio-unmute" data-toggle="tooltip" data-placement="top"
                                    title="{{ __('Unmute Audio') }}"><i class="fa fa-microphone-slash"></i></button>
                                <button class="action-video rotate" data-toggle="tooltip" data-placement="top"
                                    title="{{ __('Rotate Camera') }}"><i class="fa fa-camera"></i></button>
                            </div>
                        </div>
                    </div>
                @endif
                <div
                    class="col-12 {{ getFeature('VIDEO_CHAT', 'status') == 'active' ? 'col-md-7 col-lg-7 col-xl-9' : 'col-md-12 col-lg-12 text-chat-panel' }} chat-main">
                    <div class="row d-flex align-items-center">
                        <!-- call button options :: start -->
                        <div class="col-12 text-center chat-section">
                            <div class="btn-actions">
                                <div class="row align-items-center">
                                    <div class="col-4 col-md-4 col-lg-5 pr-0 text-left">
                                        <span class="mb-0 mt-0">
                                            <img id="partnerCountryText" class="d-none mr-1" src="images/globe.png"
                                                alt="{{ __('Country Flag') }}" width="25" height="25" />
                                        </span>
                                        @if (getFeature('TEXT_CHAT', 'status') == 'active')
                                            <button id="text" class="btn btn-theme" title="{{ __('Text chat') }}">
                                                <i class="fa fa-comments"></i>
                                                <span class="d-none d-lg-inline-block">{{ __('Text') }}</span>
                                            </button>
                                        @endif
                                        @if (getFeature('VIDEO_CHAT', 'status') == 'active')
                                            <button id="video" class="btn btn-theme" title="{{ __('Video chat') }}">
                                                <i class="fa fa-video"></i>
                                                <span class="d-none d-lg-inline-block">{{ __('Video') }}</span>
                                            </button>
                                        @endif
                                        <button id="stop" class="btn btn-theme hide">
                                            <i class="fa fa-stop"></i>
                                            <span class="d-none d-lg-inline-block">{{ __('Stop') }}</span>
                                        </button>
                                        <button id="next" class="btn btn-theme hide search-next">
                                            <i class="fa fa-random"></i>
                                            <span class="d-none d-lg-inline-block">{{ __('Next') }}</span>
                                        </button>
                                    </div>
                                    <div class="col-8 col-md-8 col-lg-7 pl-0 text-right filter-options">
                                        @if (getFeature('GENDER_FILTER', 'status') == 'active')
                                            <label class="mb-0"><i class="fa fa-users"></i></label>
                                            <select id="genderFilter">
                                                <option value="">{{ __('Gender: All') }}</option>
                                                <option value="male">{{ __('Male') }}</option>
                                                <option value="female">{{ __('Female') }}</option>
                                            </select>
                                        @else
                                            <select id="genderFilter" hidden></select>
                                        @endif
                                        @if (getFeature('COUNTRY_FILTER', 'status') == 'active')
                                            <label class="ml-2 mb-0"><i class="fa fa-flag"></i></label>
                                            <select id="countryFilter">
                                                <option value="">{{ __('Country: All') }}</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->code }}">
                                                        {{ $country->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select id="countryFilter" hidden></select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- call button options :: end -->

                        <!-- chat area :: start -->
                        <div class="col-12 chat-area">
                            <!-- about us  :: start -->
                            <div class="about">
                                <div class="text-justify description">
                                    {!! getContent('HOME_PAGE') !!}
                                </div>
                            </div>
                            <!-- about us  :: end -->
                            <!-- chat panel :: starts -->
                            <div class="chat-panel hide">
                                <div class="chat-box">
                                    <div class="chat-body"></div>
                                    <div class="chat-footer">
                                        <form id="chatForm">
                                            <div class="input-group">
                                                <input type="text" id="messageInput" class="form-control note-input"
                                                    placeholder="{{ __('Type a message') }}" autocomplete="off"
                                                    maxlength="100" disabled />
                                                <div class="input-group-append">
                                                    <button id="send" class="btn btn-outline-secondary" type="submit"
                                                        title="{{ __('Send') }}" disabled>
                                                        <i class="far fa-paper-plane"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- chat panel :: end -->
                        </div>
                        <!-- chat area :: end -->
                    </div>
                </div>
            </div>
        </div>
        <!-- video section :: end -->
    </section>
    <!-- About section :: start -->
    <section class="page-section theme-bg about-desc">
        <div class="container pb-5  about-data">
            <div class="row align-items-center pb-5 mt-5">
                <div class="col-12 col-lg-6">
                    <h1 class="main-title w-100 text-left text-white">{{ __('About The Site Title') }}</h1>
                    <p class="text-white">{{ __('About The Site Description') }}</p>
                    <div class="use-show mt-3">
                        <div class="row text-center align-items-center">
                            <div class="col-12 col-sm-4 text-white">
                                <i class="fas fa-user-check"></i>
                                <p>{{ __('Join Free') }}</p>
                            </div>
                            <div class="col-12 col-sm-4 text-white">
                                <i class="fas fa-globe"></i>
                                <p>{{ __('Connect with people') }}</p>
                            </div>
                            <div class="col-12 col-sm-4 text-white">
                                <i class="fas fa-smile"></i>
                                <p>{{ __('Have fun') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 text-center">
                    <div class="about-icon text-white">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="elementor-shape elementor-shape-bottom" data-negative="false">
            <div class="waveWrapper waveAnimation">
                <div class="waveWrapperInner bgTop">
                    <div class="wave waveTop" style="background-image: url('images/wave-top.png')"></div>
                </div>
                <div class="waveWrapperInner bgMiddle">
                    <div class="wave waveMiddle" style="background-image: url('images/wave-mid.png')"></div>
                </div>
                <div class="waveWrapperInner bgBottom">
                    <div class="wave waveBottom" style="background-image: url('images/wave-bot.png')"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- About section :: end -->

    <!-- feature section :: start -->
    <section class="about-section feature-section page-section">
        <div class="container pt-3">
            <div class="row w-100">
                <h1 class="main-title  w-100 text-center">{{ __('Random Chat Features') }}</h1>
            </div>
            <div class="row text-center mt-4">
                <div class="col-12 col-md-3">
                    <div class="icon-box">
                        <em class="fa fa-user-secret"></em>
                        <h5 class="box-title">{{ __('feature_1') }}</h5>
                        <p>{{ __('feature_description_1') }} </p>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="icon-box ">
                        <em class="fa fa-power-off"></em>
                        <h5 class="box-title ">{{ __('feature_2') }}</h5>
                        <p>{{ __('feature_description_2') }} </p>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="icon-box ">
                        <em class="fa fa-venus"></em>
                        <h5 class="box-title ">{{ __('feature_3') }}</h5>
                        <p>{{ __('feature_description_3') }} </p>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="icon-box ">
                        <em class="fa fa-globe"></em>
                        <h5 class="box-title ">{{ __('feature_4') }}</h5>
                        <p>{{ __('feature_description_4') }} </p>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="icon-box ">
                        <em class="fa fa-flag"></em>
                        <h5 class="box-title ">{{ __('feature_5') }}</h5>
                        <p>{{ __('feature_description_5') }} </p>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="icon-box ">
                        <em class="fa fa-bolt"></em>
                        <h5 class="box-title ">{{ __('feature_6') }}</h5>
                        <p>{{ __('feature_description_6') }} </p>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="icon-box ">
                        <em class="fa fa-heart"></em>
                        <h5 class="box-title ">{{ __('feature_7') }}</h5>
                        <p>{{ __('feature_description_7') }} </p>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="icon-box">
                        <em class="fa fa-moon"></em>
                        <h5 class="box-title">{{ __('feature_8') }}</h5>
                        <p>{{ __('feature_description_8') }} </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- feature section :: end -->

    <!-- Chat with Strangers section :: start -->
    <section class="page-section">
        <div class="container">
            <div class="row w-100">
                <h1 class="main-title w-100 text-center">{{ __('How To Chat With Strangers') }}</h1>
            </div>
            <div class="row mt-5 text-center">
                <p>{{ __('To begin chat, click on the Text or Video button, allow the media permission when asked and the system will instantaly find you a partner. To stop talking to a user, click on the next button. You can enjoy text chat while having a video chat too') }}
                </p>

                <p>{{ __('Apply gender and country filters to choose whom you want to talk to. Use the report button to let us know of any kind of abuse. If you do not see your webcam then please make sure the permission is given and the webcam is working') }}
                </p>
            </div>
        </div>
    </section>
    <!-- Chat with Strangers section :: end -->

    <!-- FAQ section :: start -->
    <section class="page-section about-section faq_section">
        <div class="container">
            <div class="row w-100">
                <h1 class="main-title w-100 text-center">{{ __('Frequently Asked Questions') }}</h1>
            </div>
            <div class="row align-items-center mt-5">
                <div class="col-12 ">
                    <!--Accordion wrapper-->
                    <div class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">

                        <!-- Accordion card -->
                        <div class="card">

                            <!-- Card header -->
                            <div class="card-header" role="tab" id="headingOne1">
                                <a data-toggle="collapse" data-toggle="collapse" data-parent="#accordionEx"
                                    href="#collapseOne1" aria-expanded="false" aria-controls="collapseOne1">
                                    <h5 class="mb-0">
                                        {{ __('faq_q1') }} <i class="fas fa-angle-down rotate-icon float-right"></i>
                                    </h5>
                                </a>
                            </div>

                            <!-- Card body -->
                            <div id="collapseOne1" class="collapse" role="tabpanel" aria-labelledby="headingOne1"
                                data-parent="#accordionEx">
                                <div class="card-body">
                                    {{ __('faq_a1') }}
                                </div>
                            </div>

                        </div>
                        <!-- Accordion card -->

                        <!-- Accordion card -->
                        <div class="card">

                            <!-- Card header -->
                            <div class="card-header" role="tab" id="headingTwo2">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx"
                                    href="#collapseTwo2" aria-expanded="false" aria-controls="collapseTwo2">
                                    <h5 class="mb-0">
                                        {{ __('faq_q2') }} <i class="fas fa-angle-down rotate-icon float-right"></i>
                                    </h5>
                                </a>
                            </div>

                            <!-- Card body -->
                            <div id="collapseTwo2" class="collapse" role="tabpanel" aria-labelledby="headingTwo2"
                                data-parent="#accordionEx">
                                <div class="card-body">
                                    {{ __('faq_a2') }}
                                </div>
                            </div>

                        </div>
                        <!-- Accordion card -->

                        <!-- Accordion card -->
                        <div class="card">

                            <!-- Card header -->
                            <div class="card-header" role="tab" id="headingThree3">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx"
                                    href="#collapseThree3" aria-expanded="false" aria-controls="collapseThree3">
                                    <h5 class="mb-0">
                                        {{ __('faq_q3') }} <i class="fas fa-angle-down rotate-icon float-right"></i>
                                    </h5>
                                </a>
                            </div>

                            <!-- Card body -->
                            <div id="collapseThree3" class="collapse" role="tabpanel" aria-labelledby="headingThree3"
                                data-parent="#accordionEx">
                                <div class="card-body">
                                    {{ __('faq_a3') }}
                                </div>
                            </div>

                        </div>
                        <!-- Accordion card -->

                    </div>
                    <!-- Accordion wrapper -->
                </div>
            </div>
        </div>
    </section>
    <!-- FAQ section :: end -->

    <!-- rating section :: start -->
    <section class=" page-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 text-center">
                    <div class="d-flex justify-content-center rating-section mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h1 class="main-title w-100 text-center">{{ __('5-Star Rated Application') }}</h1>
                    <p>{{ __('The application is the best way to meet someone online. With easy yet amazing functions, you will never get bored chatting. There is no need to create an account or pay anything to chat. You can get started in a single click. This makes the appliation unique. Who knows, maybe the next person you see on the chat will be the love of your life or just another good friend that you met online') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- rating section :: end -->

    <!-- Start chat section :: start -->
    <section class="page-section theme-bg">
        <div class="conatiner">
            <div class="row">
                <h1 class="main-title text-white w-100 text-center">
                    {{ __('What are you waiting for? Start a chat now') }}
                </h1>
                <a class="start-btn" href="#video-section">{{ __('Start Now') }}</a>
            </div>
        </div>
    </section>
    <!-- start chat section :: end -->



    <!-- notice modal starts -->
    <div class="modal fade" id="noticeModal" tabindex="-1" role="dialog" aria-labelledby="noticeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noticeModalLabel">{{ __('Welcome') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="welcomeForm">
                    <div class="modal-body terms_modal">
                        @if (getFeature('GENDER_FILTER', 'status') == 'active')
                            <div class="form-group">
                                <label>
                                    {{ __("I'm") }}
                                </label>
                                <select id="gender" class="form-control" required>
                                    <option value="">{{ __('Select') }}</option>
                                    <option value="male">{{ __('Male') }}</option>
                                    <option value="female">{{ __('Female') }}</option>
                                </select>
                            </div>
                        @else
                            <select id="gender" hidden></select>
                        @endif
                        <label>
                            {{ __('By clicking Continue & Start you certify that you are over :age years old and accept our Terms & Conditions and our Privacy Policy', ['age' => getSetting('MINIMUM_AGE')]) }}
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="start" class="btn btn-theme">{{ __('Confirm & Start') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- notice modal ends -->
@endsection

@section('script')
    <script src="{{ asset('js/socket.io.js') }}"></script>
    <script src="{{ asset('js/adapter.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/home.js') }}"></script>
@endsection
