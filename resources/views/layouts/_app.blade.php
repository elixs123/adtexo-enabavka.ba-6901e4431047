<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>@yield('head_title') - Petrusic.ba</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">                             
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
        <meta name="description" content="Petrusic.ba app">
        <link rel="preconnect" href="https://fonts.googleapis.com/" crossorigin>
        <link rel="preconnect" href="https://js.pusher.com/" crossorigin>
        <link rel="preconnect" href="https://www.gstatic.com/" crossorigin>
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/apple-icon-57x57.png')}}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/apple-icon-60x60.png')}}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/apple-icon-72x72.png')}}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/apple-icon-76x76.png')}}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/apple-icon-114x114.png')}}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/apple-icon-120x120.png')}}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/apple-icon-144x144.png')}}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/apple-icon-152x152.png')}}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/apple-icon-180x180.png')}}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('/android-icon-192x192.png')}}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-32x32.png')}}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('/favicon-96x96.png')}}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon-16x16.png')}}">
        <link rel="manifest" href="{{ asset('/manifest.json'.assetVersion())}}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('/ms-icon-144x144.png') }}">
        <meta name="theme-color" content="#ffffff">
        <link rel="icon" type="image/x-icon" href="{{ asset('/favicon.ico') }}" />
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-touch-fullscreen" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">

        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 

        <link href="{{ asset('/assets/admin/assets/plugins/pace/pace-theme-flash.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/assets/admin/assets/plugins/bootstrapv3/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/assets/admin/assets/plugins/font-awesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/assets/admin/assets/plugins/jquery-scrollbar/jquery.scrollbar.css') }}" rel="stylesheet" type="text/css" media="screen" />
        <link href="{{ asset('assets/admin/assets/plugins/bootstrap-select2/4.0.5/css/select2.css') }}" rel="stylesheet" type="text/css" media="screen" />
        <link href="{{ asset('/assets/admin/assets/plugins/switchery/css/switchery.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
        <link href="{{ asset('/assets/admin/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/assets/admin/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/assets/admin/assets/plugins/datatables-responsive/css/datatables.responsive.css') }}" rel="stylesheet" type="text/css" media="screen" />

        <link href="{{ asset('/assets/admin/pages/css/pages-icons.css') }}" rel="stylesheet" type="text/css">
        <link class="main-stylesheet" href="{{ asset('/assets/admin/pages/css/pages.css'.assetVersion()) }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/assets/admin/assets/css/style.css'.assetVersion()) }}" rel="stylesheet" type="text/css" />
        @yield('css')
        @if(config('broadcasting.connections.pusher.enabled'))
        <script type="text/javascript" src="https://js.pusher.com/4.1/pusher.min.js"></script>
        <script type="text/javascript">
            Pusher.logToConsole = @if(config('app.env') == 'local'){{ 'false' }}@else{{ 'false' }}@endif;
            @php
                $options = config('broadcasting.connections.pusher.options');
                $options['auth']['headers']['X-CSRF-Token'] = csrf_token();
            @endphp
            var pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {!! json_encode($options, JSON_UNESCAPED_SLASHES) !!});
            var auth_hash = '{{ hash('sha1', auth()->id()) }}';
            var pusher_me = null;
            var socket_id = null;
        </script>
        @endif
        <!--[if lte IE 9]>
        <link href="{{ asset('assets/admin/assets/plugins/codrops-dialogFx/dialog.ie.css') }}" rel="stylesheet" type="text/css" media="screen" />
        <![endif]-->
    </head>
    <body class="fixed-header {{request()->segment(1)}}">
        <!-- BEGIN SIDEBPANEL-->
        <nav class="page-sidebar" data-pages="sidebar">
            <!-- BEGIN SIDEBAR MENU HEADER-->
            <div class="sidebar-header">
                <img src="{{ asset('assets/admin/assets/img/logo.png') }}" alt="logo" class="brand" data-src="{{ asset('assets/admin/assets/img/logo.png') }}" data-src-retina="{{ asset('assets/admin/assets/img/logo_2x.png') }}" height="30">
            </div>
            <!-- END SIDEBAR MENU HEADER-->
            <!-- START SIDEBAR MENU -->
            <div class="sidebar-menu">
                <!-- BEGIN SIDEBAR MENU ITEMS-->
                <ul class="menu-items">
                    @can('view-subject')
                    <li class="m-t-30 @if($active = request()->is('*subject*')){{ 'active' }}@endif">
                        <a href="{{ url('/subject') }}" class="detailed">
                            <span class="title">Predmeti</span>
                        </a>
                        <span class="icon-thumbnail {{ $active ? 'bg-success' : '' }}"><i class="fa fa-book"></i></span>
                    </li>
                    @endcan                    
                    @can('view-hearing') 
                    <li class="@if($active = request()->is('*hearing*')){{ 'active' }}@endif">
                        <a href="{{ url('/hearing') }}" class="detailed">
                            <span class="title">Ročišta</span>
                        </a>
                        <span class="icon-thumbnail {{ $active ? 'bg-success' : '' }}"><i class="fa fa-calendar"></i></span>
                    </li>
                    @endcan
                    @can('view-court')
                    <li class="@if($active = request()->is('*judgment*')){{ 'active' }}@endif">
                        <a href="{{ url('/judgment') }}" class="detailed">
                            <span class="title">Presude</span>
                        </a>
                        <span class="icon-thumbnail {{ $active ? 'bg-success' : '' }}"><i class="fa fa-gavel"></i></span>
                    </li>
                    @endcan
                    @can('view-task')
                    <li class="@if($active = request()->is('*task*')){{ 'active' }}@endif">
                        <a href="{{ url('/task') }}" class="detailed">
                            <span class="title">Zadaci</span>
                        </a>
                        <span class="icon-thumbnail {{ $active ? 'bg-success' : '' }}"><i class="fa fa-tasks"></i></span>
                    </li>
                    @endcan
                    @can('view-note')
                    <li class="@if($active = request()->is('*note*')){{ 'active' }}@endif">
                        <a href="{{ url('/note') }}" class="detailed">
                            <span class="title">Napomene</span>
                        </a>
                        <span class="icon-thumbnail {{ $active ? 'bg-success' : '' }}"><i class="fa fa-sticky-note"></i></span>
                    </li>
                    @endcan
                    @can('view-document')
                    <li class="@if($active = request()->is('*document*')){{ 'active' }}@endif">
                        <a href="{{ url('/document') }}" class="detailed">
                            <span class="title">Dokumenti</span>
                        </a>
                        <span class="icon-thumbnail {{ $active ? 'bg-success' : '' }}"><i class="fa fa-file"></i></span>
                    </li>
                    @endcan
                    @can('view-protocol')
                    <li class="@if($active = request()->is('*protocol*')){{ 'active' }}@endif">
                        <a href="{{ url('/protocol') }}" class="detailed">
                            <span class="title">Protokol</span>
                        </a>
                        <span class="icon-thumbnail {{ $active ? 'bg-success' : '' }}"><i class="fa fa-envelope"></i></span>
                    </li>
                    @endcan					
                    @can('view-client')
                    <li class="@if($active = request()->is('*client*')){{ 'open active' }}@endif">
                        <a href="javascript:;"><span class="title">Komitenti</span>
                            <span class="arrow {{ $active ? 'open active' : '' }}"></span></a>
                        <span class="icon-thumbnail {{ $active ? 'bg-success' : '' }}"><i class="fa fa-briefcase"></i></span>
                        <ul class="sub-menu" style="display: {{ $active ? 'block' : 'none' }}">
                            @foreach($client_types as $key => $client_type)
                            <li>
                                <a href="{{ url('/client?type_id=' . $key) }}">{{ $client_type }}</a>
                                <span class="icon-thumbnail"><i class="fa fa-briefcase"></i></span>
                            </li>
                            @endforeach
                        </ul>
                        </a>
                    </li>
                    @endcan
                    @can('view-invoice')
                        <li class="@if($active = request()->is('*invoice*')){{ 'active' }}@endif">
                            <a href="{{ url('/invoice') }}" class="detailed">
                                <span class="title">Računi</span>
                            </a>
                            <span class="icon-thumbnail {{ $active ? 'bg-success' : '' }}"><i class="fa fa-money"></i></span>
                        </li>
                    @endcan
                    @can('view-codebook')
                    <li class="@if($active = request()->is('*code-book*')){{ 'open active' }}@endif">
                        <a href="javascript:;"><span class="title">Šifarnici</span>
                            <span class="arrow {{ $active ? 'open active' : '' }}"></span></a>
                        <span class="icon-thumbnail {{ $active ? 'bg-success' : '' }}"><i class="fa fa-code"></i></span>
                        <ul class="sub-menu" style="display: {{ $active ? 'block' : 'none' }}">
                            @foreach($codebook_types as $key => $codebook_type)
                            <li>
                                <a href="{{ url('/code-book?type=' . $key) }}">{{ $codebook_type }}</a>
                                <span class="icon-thumbnail"><i class="fa fa-server"></i></span>
                            </li>
                            @endforeach
                        </ul>              
                        </a>
                    </li> 
                    @endcan                    
                    @can('view-user') 
                    <li class="@if($active = request()->is('*role*') || request()->is('*permission*')  || request()->is('*user*')){{ 'open active' }}@endif">
                        <a href="javascript:;"><span class="title">Korisnici</span>
                            <span class="arrow {{ $active ? 'open active' : '' }}"></span></a>
                        <span class="icon-thumbnail {{ $active ? 'bg-success' : '' }}"><i class="fa fa-users"></i></span>
                        <ul class="sub-menu" style="display: {{ $active ? 'block' : 'none' }}">
                            @can('view-user') 
                            <li>
                                <a href="{{ url('/user') }}">Korisnici</a>
                                <span class="icon-thumbnail"><i class="fa fa-users"></i></span>
                            </li>
                            @endcan
                            @can('view-role') 
                            <li>
                                <a href="{{ url('/role') }}">Role</a>
                                <span class="icon-thumbnail"><i class="fa fa-server"></i></span>
                            </li>
                            @endcan
                            @can('view-permission') 
                            <li>
                                <a href="{{ url('/permission') }}">Dozvole</a>
                                <span class="icon-thumbnail"><i class="pg-power"></i></span>
                            </li>
                            @endcan
                        </ul>              
                        </a>
                    </li> 
                    @endcan
                    @can('view-activity') 
                    <li class="@if($active = request()->is('*activity*')){{ 'open active' }}@endif">
                        <a href="{{ url('/activity') }}"><span class="title">Aktivnosti</span></a>
                        <span class="icon-thumbnail {{ $active ? 'bg-success' : '' }}"><i class="fa fa-clock-o"></i></span>
                    </li> 
                    @endcan					
                    <li>
                        <a href="javascript:;"><span class="title">Profil</span>
                            <span class=" arrow"></span></a>
                        <span class="icon-thumbnail"><i class="fa fa-user"></i></span>            
                        <ul class="sub-menu">
                            <li>
                                <a data-toggle="modal" data-target="#form-modal" data-href="{{ url('/user/' . auth()->id() . '/edit') }}">Uredi</a>
                                <span class="icon-thumbnail"><i class="fa fa-server"></i></span>
                            </li>
                            <li>
                                <a href="{{ url('/logout') }}" data-logout>Odjava</a>
                                <span class="icon-thumbnail"><i class="pg-power"></i></span>
                            </li>						  
                        </ul>              
                        </a>
                    </li>		  
                </ul>
                <div class="clearfix"></div>
            </div>
            <!-- END SIDEBAR MENU -->
        </nav>
        <!-- END SIDEBAR -->
        <!-- END SIDEBPANEL-->
        <!-- START PAGE-CONTAINER -->
        <div class="page-container ">
            <!-- START HEADER -->
            <div class="header ">
                @if(isMobile() || isTablet())
                <!-- START MOBILE CONTROLS -->
                <div class="container-fluid relative">
                    <!-- LEFT SIDE -->
                    <div class="pull-left full-height visible-sm visible-xs">
                        <!-- START ACTION BAR -->
                        <div class="header-inner">
                            <a href="javascript:;" class="btn-link toggle-sidebar visible-sm-inline-block visible-xs-inline-block padding-5 m-r-10" data-toggle="sidebar">
                                <span class="icon-set menu-hambuger"></span>
                            </a>
                            <div class="pull-right" style="margin-top: 3px;">
                                @include('partials.notifications')
                            </div>
                        </div>
                        <!-- END ACTION BAR -->
                    </div>
                    <div class="pull-center hidden-md hidden-lg">
                        <div class="header-inner">
                            <div class="brand inline">
                                <a href="{{ url('/') }}">
                                    <img src="{{ asset('assets/admin/assets/img/logo_small.png') }}" alt="logo" data-src="{{ asset('assets/admin/assets/img/logo_small.png') }}" data-src-retina="{{ asset('assets/admin/assets/img/logo_small_2x.png') }}" height="30" >
                                </a>
                            </div>
                        </div>
                    </div>
                <!-- RIGHT SIDE -->
                    <div class="pull-right full-height visible-sm visible-xs">
                    <!-- START ACTION BAR -->
                        <div class="header-inner">
                            <a href="javascript:;" class="btn-link icon-set menu-hambuger-plus m-l-20 sm-no-margin" data-toggle="quickview" data-toggle-element="#quickview"></a>
                        </div>
                        <!-- END ACTION BAR -->
                    </div>
                </div>
                <!-- END MOBILE CONTROLS -->
                @else
                <div class="pull-left sm-table hidden-xs hidden-sm">
                    <div class="header-inner">
                        <div class="brand p-l-30 inline">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('assets/admin/assets/img/logo.png') }}" style="margin-left: 30px" alt="logo" data-src="{{ asset('assets/admin/assets/img/logo.png') }}" data-src-retina="{{ asset('assets/admin/assets/img/logo_2x.png') }}" height="30">
                            </a>
                        </div>
                        @include('partials.notifications')
                    </div>
                </div>
                <div class=" pull-right hidden-xs hidden-sm">
                    <div class="header-inner">
                        
                        <a href="javascript:;" class="btn-link icon-set menu-hambuger-plus m-l-20 sm-no-margin" data-toggle="quickview" data-toggle-element="#quickview"></a>
                    </div>
                </div>
                <div class=" pull-right">
                    <!-- START User Info-->
                    <div class="visible-lg visible-md m-t-10">
                        <div class="pull-left p-r-10 p-t-10 fs-16 font-heading">
                            <span class="semi-bold">{{ is_null(auth()->user()->client) ? auth()->user()->email : auth()->user()->client->name }}</span>
                        </div>
                        <div class="dropdown pull-right">
                            <button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								@if(auth()->user()->photo != '')							
								<span class="thumbnail-wrapper d32 circular inline">
								<img src="{{ asset('assets/pictures/user/small_' . auth()->user()->photo) }}" alt="" data-src="{{ asset('assets/pictures/user/medium_' . auth()->user()->photo) }}" data-src-retina="{{ asset('assets/pictures/user/medium_' . auth()->user()->photo) }}" width="32" height="32">
								</span>
								@else
								<span class="thumbnail-wrapper d32 circular inline">
								<img src="{{ asset('assets/pictures/user/no-photo.jpg') }}" alt="" data-src="{{ asset('assets/pictures/user/no-photo.jpg') }}" data-src-retina="{{ asset('assets/pictures/user/no-photo.jpg') }}" width="32" height="32">
								</span>									
								@endif
                            </button>
                            <ul class="dropdown-menu profile-dropdown" role="menu">
                                <li><a data-toggle="modal" data-target="#form-modal" data-href="{{ url('/user/' . auth()->id() . '/edit') }}"><i class="pg-settings_small"></i> Tvoj profil</a></li>
                                <li><a><i class="pg-outdent"></i> {{ request()->ip() }}</a></li>
                                <li class="bg-master-lighter">
                                    <a href="{{ url('/logout') }}" class="clearfix" data-logout>
                                        <span class="pull-left">Odjava</span>
                                        <span class="pull-right"><i class="pg-power"></i></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- END User Info-->
                </div>
                @endif
            </div>
            <!-- END HEADER -->	

            @yield('content')
            
            @include('partials.quick_view')

            @include('partials.modal_placeholder')
            <!-- START COPYRIGHT -->
            <div class="container-fluid container-fixed-lg footer">
                <div class="copyright sm-text-center">
                    <p class="small no-margin pull-left sm-pull-reset">
                        <span class="hint-text">{{ date('Y') }}. </span>
                        <span class="font-montserrat">Licenca: Petrušić & Co</span>
                    </p>
                    <p class="small no-margin pull-right sm-pull-reset">
                        <span class="hint-text">Development & Copyright:</span> <a target="_blank" href="https://lampa.ba">Lampa</a> 
                    </p>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- END COPYRIGHT -->
            
        </div>
        <!-- END PAGE CONTENT WRAPPER -->
    </div>
    <!-- END PAGE CONTAINER -->
    <!-- start: loader -->
    <div id="loader"><div class="loading">loading ...</div></div>
    <!-- end: loader -->
    @include('partials.mustache')
    <!-- BEGIN VENDOR JS -->
	<!--
    <script src="{{ asset('assets/admin/assets/plugins/pace/pace.min.js') }}" type="text/javascript"></script>
	-->
    <script src="{{ asset('assets/admin/assets/plugins/jquery/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/assets/plugins/modernizr.custom.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/assets/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/assets/plugins/bootstrapv3/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/assets/plugins/jquery/jquery-easy.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/assets/plugins/jquery-unveil/jquery.unveil.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/assets/plugins/jquery-bez/jquery.bez.min.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/plugins/jquery-ios-list/jquery.ioslist.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/assets/plugins/jquery-actual/jquery.actual.min.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/assets/plugins/bootstrap-select2/4.0.5/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/assets/plugins/classie/classie.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/plugins/switchery/js/switchery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/assets/plugins/datatables-responsive/js/datatables.responsive.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/assets/plugins/datatables-responsive/js/lodash.min.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/assets/plugins/jquery-validation/js/localization/messages_hr.min.js') }}" type="text/javascript"></script>	
    <script src="{{ asset('assets/admin/assets/plugins/mustache/2.3.2/mustache.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/assets/plugins/scrollTo/jquery.scrollTo.2.1.1.min.js') }}" type="text/javascript"></script>
    <!-- END VENDOR JS -->
    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="{{ asset('assets/admin/pages/js/pages.js'.assetVersion()) }}"></script>
    <!-- END CORE TEMPLATE JS -->
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="{{ asset('assets/admin/assets/js/tables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/pages/js/script.js'.assetVersion()) }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/assets/js/scripts.js'.assetVersion()) }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/assets/js/quick-view.js'.assetVersion()) }}" type="text/javascript"></script>
    @yield('script')
    @yield('script_inline')
    @if(config('broadcasting.connections.pusher.enabled'))
    <script src="{{ asset('assets/admin/assets/js/pusher.js'.assetVersion()) }}" type="text/javascript"></script>
    @endif
    @if(config('services.firebase.enabled'))
    <script type="text/javascript" src="https://www.gstatic.com/firebasejs/6.3.3/firebase-app.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/firebasejs/6.3.3/firebase-messaging.js"></script>
    <script type="text/javascript">
        const firebaseConfig = {!! json_encode(config('services.firebase.config'), JSON_UNESCAPED_SLASHES) !!}
        firebase.initializeApp(firebaseConfig);
    </script>
    @endif
    <script type="text/javascript">
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js{{ assetVersion() }}').then(function (registration) {
                //console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }).catch(function (err) {
                console.log('ServiceWorker registration failed: ', err);
            });
            @if(config('services.firebase.enabled'))
            navigator.serviceWorker.register('/firebase-messaging-sw.js?v=20191017', {scope: '/firebase-cloud-messaging-push-scope'}).then(function (registration) {
                //console.log('Firebase ServiceWorker registration successful with scope: ', registration.scope);
                const messaging = firebase.messaging();
                messaging.useServiceWorker(registration);
                resetUI();
            }).catch(function (err) {
                console.log('Firebase ServiceWorker registration failed: ', err);
            });
            @endif
        }
    </script>
    @if(config('services.firebase.enabled'))
    <script src="{{ asset('assets/admin/assets/js/firebase.js'.assetVersion()) }}" type="text/javascript"></script>
    @endif
    <!-- END PAGE LEVEL JS -->
</body>
</html>      