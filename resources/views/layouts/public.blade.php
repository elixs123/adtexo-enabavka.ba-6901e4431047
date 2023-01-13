<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>@yield('head_title') - enabavka.ba</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="description" content="enabavka.ba app">
        <link rel="preconnect" href="https://fonts.googleapis.com/" crossorigin>
        <link rel="preconnect" href="https://js.pusher.com/" crossorigin>
        <link rel="preconnect" href="https://www.gstatic.com/" crossorigin>
        @include('partials.favicons')
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600&display=swap&subset=latin-ext" rel="stylesheet">
        <link href="{{ asset('assets/theme/vendors/css/vendors.min.css').assetVersion() }}" rel="stylesheet" type="text/css">
        @yield('css-vendor')
        <link href="{{ asset('assets/theme/css/app.min.css').assetVersion() }}" rel="stylesheet" type="text/css">
        @yield('css')
        <style>
            html body .content,
            body.vertical-layout.vertical-menu-modern .footer {
                margin-left: 0;
            }
            html body.navbar-sticky .app-content .content-wrapper {
                margin-top: 0 !important;
            }
        </style>
        @include('partials.pusher')
    </head>
    <body class="vertical-layout vertical-menu-modern semi-dark-layout 2-columns navbar-sticky footer-static {{ isset($body_class) ?  $body_class : '' }} " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="semi-dark-layout">
        @if(false)
        <!-- start: header -->
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top navbar-light navbar-shadow">
            <div class="navbar-wrapper">
                <div class="navbar-container content">
                    <div class="navbar-collapse" id="navbar-mobile">
                        <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                            <ul class="nav navbar-nav">
                                <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon feather icon-menu"></i></a></li>
                            </ul>
                        </div>
                        <ul class="nav navbar-nav float-right">
                            <!-- start: user -->
                            <li class="dropdown dropdown-user nav-item">
                                <a class="dropdown-toggle nav-link dropdown-user-link" href="javascript:" data-toggle="dropdown">
                                    <div class="user-nav d-sm-flex d-none">
                                        <span class="user-name text-bold-600">{{ $client->name }}</span>
                                        <span class="user-status">client</span>
                                    </div>
                                    <span><img class="round" src="{{ asset('assets/img/no_photo.jpg') }}" alt="avatar" height="40" width="40"></span>
                                </a>
                            </li>
                            <!-- end: user -->
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <!-- end: header -->
        <!-- start: main menu -->
        <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mr-auto"><a class="navbar-brand" href="{{ $client->public_url }}">
                            <div class="brand-logo"></div>
                            <h2 class="brand-text mb-0">e<span>nabavka.ba</span></h2>
                        </a></li>
                    <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i><i class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block primary" data-ticon="icon-disc"></i></a></li>
                </ul>
            </div>
            <div class="shadow-bottom"></div>
            <div class="main-menu-content">
                <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                    <li class="nav-item active">
                        <a href="{{ $client->public_url }}">
                            <i class="feather icon-file"></i>
                            <span class="menu-title">{{ trans('document.title') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- end: main menu -->
        @endif
        <!-- start: content -->
        <div class="app-content content">
            <div class="content-overlay"></div>
            <div class="header-navbar-shadow"></div>
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
        <!-- end: content -->
        <div class="sidenav-overlay"></div>
        <div class="drag-target"></div>
        @if(false)
        <!-- start: footer -->
        <footer class="footer footer-light">
            <p class="clearfix blue-grey lighten-2 mb-0">
                <span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; {{ now()->format('Y') }}<a class="text-bold-800 grey darken-2" href="https://enabavka.ba" target="_blank" rel="noopener">enabavka.ba,</a>All rights Reserved</span><span class="float-md-right d-none d-md-block">Hand-crafted & Made with<i class="feather icon-heart pink"></i></span>
                <button class="btn btn-primary btn-icon scroll-top" type="button"><i class="feather icon-arrow-up"></i></button>
            </p>
        </footer>
        <!-- end: footer -->
        @endif
        <!-- start: loader -->
        <div id="loader"><div class="loading">loading ...</div></div>
        <!-- end: loader -->
        <!-- start: scripts -->
        <script src="{{ asset('assets/theme/vendors/js/vendors.min.js').assetVersion() }}" type="text/javascript"></script>
        @yield('script-vendor')
        <script src="{{ asset('assets/theme/js/app.min.js').assetVersion() }}" type="text/javascript"></script>
        <script src="{{ asset('assets/js/invoicing.js').assetVersion() }}" type="text/javascript"></script>
        @yield('script')
        @yield('script_inline')
        @stack('head')
        <script type="text/javascript">
            AjaxForm.onShowModal();
            
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js{{ assetVersion() }}').then(function (registration) {
                    //console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }).catch(function (err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            }
        </script>
        @include('partials.firebase')
        <!-- end: scripts -->
    </body>
</html>
