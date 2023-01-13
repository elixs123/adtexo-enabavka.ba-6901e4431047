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
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600&amp;subset=latin-ext&amp;display=swap" rel="stylesheet">
        <link href="{{ asset('assets/theme/vendors/css/vendors.min.css').assetVersion() }}" rel="stylesheet" type="text/css">
        @yield('css-vendor')
        <link href="{{ asset('assets/theme/css/app.min.css').assetVersion() }}" rel="stylesheet" type="text/css">
        @yield('css')
    </head>
    <body class="vertical-layout vertical-menu-modern semi-dark-layout 1-column  navbar-floating footer-static bg-full-screen-image  blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column" data-layout="semi-dark-layout">
        <div class="app-content content">
            <div class="content-overlay"></div>
            <div class="header-navbar-shadow"></div>
            <div class="content-wrapper">
                <div class="content-header row">
                </div>
                <div class="content-body">
                    <section class="row flexbox-container">
                        @yield('content')
                    </section>
                </div>
            </div>
        </div>
        <!-- start: scripts -->
        <script src="{{ asset('assets/theme/vendors/js/vendors.min.js').assetVersion() }}" type="text/javascript"></script>
        @yield('script-vendor')
        <script src="{{ asset('assets/theme/js/app.min.js').assetVersion() }}" type="text/javascript"></script>
        @yield('script')
        @yield('script_inline')
        <!-- end: scripts -->
    </body>
</html>      