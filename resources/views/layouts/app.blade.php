<?php
$scoped_header = isset($scoped_header) ? $scoped_header : ScopedDocument::exist();
$scoped_footer = isset($scoped_footer) ? $scoped_footer : ScopedDocument::exist();
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
		<meta name="robots" content="noindex">
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

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        @yield('css')
        @include('partials.pusher')
    </head>
    <body class="vertical-layout vertical-menu-modern semi-dark-layout 2-columns navbar-sticky @if($scoped_footer){{ 'fixed-footer' }}@else{{ 'footer-static' }}@endif {{ isset($body_class) ?  $body_class : '' }} " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="semi-dark-layout">
        <!-- start: header -->
        <input id="printerType" type="hidden" value="{{ auth()->user()->rPerson->printer_type }}" />
        <input id="printerReceiptUrl" type="hidden" value="{{ auth()->user()->rPerson->printer_receipt_url }}" />
        <input id="printerAccessToken" type="hidden" value="{{ auth()->user()->rPerson->printer_access_token }}" />

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
                            @if($scoped_header)
                            <ul class="nav navbar-nav bookmark-icons d-none d-lg-block d-xl-block">
                                <li class="nav-item">
                                    <a class="nav-link header-document-info d-flex" href="{{ route('document.show', [ScopedDocument::id()]) }}" data-toggle="tooltip" title="{{ trans('document.actions.show') }}">
                                        <i class="ficon feather icon-file" style="color: {{ ScopedDocument::color() }}"></i>
                                        <span>
                                            <small>{{ ScopedDocument::client()->full_name }}</small><br>
                                            <strong>{{ ScopedDocument::type() }} #{{ ScopedDocument::id() }}</strong>
                                        </span>
                                        <span>| <strong data-document-subtotal class="badge" style="background-color: {{ ScopedDocument::backgroundColor() }}">{{ format_price(ScopedDocument::totalDiscountedValue(), 2) }} {{ ScopedDocument::currency() }}</strong> | <strong data-document-total-items>{{ ScopedDocument::totalItems() }}</strong> proizvoda</span>
                                    </a>
                                </li>
                            </ul>
                            @endif
                        </div>
                        <ul class="nav navbar-nav float-right">
                            <!-- start: language -->
                            <li class="dropdown dropdown-language nav-item">
                                <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @if(session('db_name') == 'ba')
                                    <i class="flag-icon flag-icon-ba"></i>
                                    
                                        <span class="menu-title">Bosna i Hercegovina</span>
                                    @elseif(session('db_name') == 'rs')
                                    <i class="flag-icon flag-icon-rs"></i>
                                        <span class="menu-title">Srbija</span>
                                    @else
                                    <i class="flag-icon flag-icon-hr"></i>
                                        <span class="menu-title">Hrvatska</span>
                                    @endif
                                </a>
                            </li>
                            <!-- end: language -->
                            <!-- start: full screen -->
                            <li class="dropdown dropdown-shortcuts nav-item">
                                
                                <a class="dropdown-toggle nav-link" id="dropdown-shortcuts" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="feather icon-plus"></i>
                                </a>

                                


                                <div class="dropdown-menu" aria-labelledby="dropdown-shortcuts">
                                    <a class="dropdown-item"  type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="feather icon-file"></i> Nova narudžba</a>
                                        
                                </div>

                                
                            </li>
                            <!-- end: full screen -->
                            @if(false)
                            <!-- start: notifications -->
                            <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon feather icon-bell"></i><span class="badge badge-pill badge-primary badge-up">5</span></a>
                                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                    <li class="dropdown-menu-header">
                                        <div class="dropdown-header m-0 p-2">
                                            <h3 class="white">5 New</h3><span class="grey darken-2">App Notifications</span>
                                        </div>
                                    </li>
                                    <li class="scrollable-container media-list">
                                        <a class="d-flex justify-content-between" href="javascript:void(0)">
                                            <div class="media d-flex align-items-start">
                                                <div class="media-left"><i class="feather icon-plus-square font-medium-5 primary"></i></div>
                                                <div class="media-body">
                                                    <h6 class="primary media-heading">You have new order!</h6><small class="notification-text"> Are your going to meet me tonight?</small>
                                                </div><small>
                                                    <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">9 hours ago</time></small>
                                            </div>
                                        </a>
                                        <a class="d-flex justify-content-between" href="javascript:void(0)">
                                            <div class="media d-flex align-items-start">
                                                <div class="media-left"><i class="feather icon-download-cloud font-medium-5 success"></i></div>
                                                <div class="media-body">
                                                    <h6 class="success media-heading red darken-1">99% Server load</h6><small class="notification-text">You got new order of goods.</small>
                                                </div><small>
                                                    <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">5 hour ago</time></small>
                                            </div>
                                        </a>
                                        <a class="d-flex justify-content-between" href="javascript:void(0)">
                                            <div class="media d-flex align-items-start">
                                                <div class="media-left"><i class="feather icon-alert-triangle font-medium-5 danger"></i></div>
                                                <div class="media-body">
                                                    <h6 class="danger media-heading yellow darken-3">Warning notifixation</h6><small class="notification-text">Server have 99% CPU usage.</small>
                                                </div><small>
                                                    <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">Today</time></small>
                                            </div>
                                        </a><a class="d-flex justify-content-between" href="javascript:void(0)">
                                            <div class="media d-flex align-items-start">
                                                <div class="media-left"><i class="feather icon-check-circle font-medium-5 info"></i></div>
                                                <div class="media-body">
                                                    <h6 class="info media-heading">Complete the task</h6><small class="notification-text">Cake sesame snaps cupcake</small>
                                                </div><small>
                                                    <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">Last week</time></small>
                                            </div>
                                        </a><a class="d-flex justify-content-between" href="javascript:void(0)">
                                            <div class="media d-flex align-items-start">
                                                <div class="media-left"><i class="feather icon-file font-medium-5 warning"></i></div>
                                                <div class="media-body">
                                                    <h6 class="warning media-heading">Generate monthly report</h6><small class="notification-text">Chocolate cake oat cake tiramisu marzipan</small>
                                                </div><small>
                                                    <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">Last month</time></small>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="dropdown-menu-footer"><a class="dropdown-item p-1 text-center" href="javascript:void(0)">Read all notifications</a></li>
                                </ul>
                            </li>
                            <!-- end: notifications -->
                            @endif
                            <!-- start: user -->
                            <li class="dropdown dropdown-user nav-item">
                                <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                    <div class="user-nav d-sm-flex d-none">
                                        <span class="user-name text-bold-600">{{ Auth::user()->email}}</span>
                                        <span class="user-status">{{ implode(', ', auth()->user()->roles->pluck('name')->toArray()) }}</span>
                                    </div>
                                    <span><img class="round" src="@if(auth()->user()->photo != ''){{ asset('assets/pictures/user/small_' . auth()->user()->photo) }}@else{{ asset('assets/img/no_photo.jpg') }}@endif" alt="avatar" height="40" width="40"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" data-toggle="modal" data-target="#form-modal1" data-href="{{ route('user.edit', [auth()->id()]) }}"><i class="feather icon-user"></i> {{ trans('profile.actions.edit') }}</a>
                                    @if(Session::get('real_user_id') && (Auth::user()->id != Session::get('real_user_id')))
                                    <a class="dropdown-item" href="{{ url('/user/login-as-real-user') }}"><i class="feather icon-unlock"></i>{{ trans('skeleton.login_back') }}</a>
                                    @endif

                                    @if(false)
                                    <a class="dropdown-item" href="#"><i class="feather icon-mail"></i> My Inbox</a>
                                    <a class="dropdown-item" href="#"><i class="feather icon-check-square"></i> Task</a>
                                    <a class="dropdown-item" href="#"><i class="feather icon-message-square"></i> Chats</a>
                                    @endif
                                    <!--<div class="dropdown-divider"></div>-->
                                    <a class="dropdown-item" href="{{ route('logout') }}"><i class="feather icon-power"></i> {{ trans('profile.actions.logout') }}</a>
                                </div>
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
                    <li class="nav-item mr-auto"><a class="navbar-brand" href="{{ route('dashboard') }}">
                            <div class="brand-logo"></div>
                            <h2 class="brand-text mb-0">e<span>nabavka.ba</span></h2>
                        </a></li>
                    <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i><i class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block primary" data-ticon="icon-disc"></i></a></li>
                </ul>
            </div>
            <div class="shadow-bottom"></div>
            <div class="main-menu-content">
                <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                    <li class="nav-item @if(request()->is('/')){{ 'active' }}@endif">
                        <a href="{{ route('dashboard') }}">
                            <i class="feather icon-home"></i>
                            <span class="menu-title">{{ trans('skeleton.dashboard') }}</span>
                        </a>
                    </li>
                    @can('view-invoicing')
                    <li class="nav-item @if(request()->is('*invoicing*')){{ 'active' }}@endif">
                        <a href="{{ route('invoicing') }}">
                            <i class="feather icon-codepen"></i>
                            <span class="menu-title">{{ trans('skeleton.invoicing') }}</span>
                        </a>
                    </li>
                    @endcan
                    <li class=" nav-item">
                        <a href="javascript:">
                            <i class="feather icon-trending-up"></i>
                            <span class="menu-title">Naplata</span>
                        </a>
                        <ul class="menu-content">
                            <li class="@if(request()->is('*billing*')){{ 'active' }}@endif">
                                <a href="{{ route('billing.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">Pregled uplata</span>
                                </a>
                            </li>
                            <li class="@if(request()->is('*demand*')){{ 'active' }}@endif">
                                <a href="{{ route('demand.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">Pregled dugovanja</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @can('view-payment')
                    <li class="nav-item @if(request()->is('*payment*')){{ 'active' }}@endif">
                        <a href="{{ route('payment.index') }}">
                            <i class="feather icon-file-plus"></i>
                            <span class="menu-title">{{ trans('payment.title') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('view-person')
                    <li class="nav-item @if(request()->is('*person*')){{ 'active' }}@endif">
                        <a href="{{ route('person.index') }}">
                            <i class="feather icon-users"></i>
                            <span class="menu-title">{{ trans('person.title') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('view-client')
                    <li class="nav-item @if(request()->is('*client*')){{ 'active' }}@endif">
                        <a href="{{ route('client.index') }}">
                            <i class="feather icon-briefcase"></i>
                            <span class="menu-title">{{ trans('client.title') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('view-product')
                    <li class=" nav-item">
                        <a href="javascript:">
                            <i class="feather icon-box"></i>
                            <span class="menu-title">Katalog</span>
                        </a>
                        <ul class="menu-content">
                            @can('view-stock')
                            <li class="@if(request()->is('*stock*')){{ 'active' }}@endif">
                                <a href="{{ route('stock.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">{{ trans('stock.title') }}</span>
                                </a>
                            </li>
                            @endcan
                            @can('view-brand')
                            <li class="@if(request()->is('*brand*')){{ 'active' }}@endif">
                                <a href="{{ route('brand.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">{{ trans('brand.title') }}</span>
                                </a>
                            </li>
                            @endcan
                            @can('view-category')
                            <li class="@if(request()->is('*category*')){{ 'active' }}@endif">
                                <a href="{{ route('category.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">{{ trans('category.title') }}</span>
                                </a>
                            </li>
                            @endcan
                            @can('view-product')
                            <li class="@if(request()->is('*product*')){{ 'active' }}@endif">
                                <a href="{{ route('product.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">{{ trans('product.title') }}</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcan
                    @can('view-document')
                    <li class="nav-item @if(request()->is('orders')){{ 'active' }}@endif">
                        <a href="{{ route('orders') }}">
                            <i class="feather icon-file"></i>
                            <span class="menu-title">{{ userIsClient() ? trans('document.title_client') : trans('document.title') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('view-contract')
                    <li class="nav-item @if(request()->is('*contract*')){{ 'active' }}@endif">
                        <a href="{{ route('contract.index') }}">
                            <i class="feather icon-file-text"></i>
                            <span class="menu-title">{{ trans('contract.title') }}</span>
                        </a>
                    </li>
                    @endcan
                    @if(can('view-action') && (auth()->user()->isAdmin() || auth()->user()->isSupervisor()))
                    <li class="nav-item @if(request()->is('*action*')){{ 'active' }}@endif">
                        <a href="{{ route('action.index') }}">
                            <i class="feather icon-award"></i>
                            <span class="menu-title">{{ trans('action.title') }}</span>
                        </a>
                    </li>
                    @endif
                    @can('view-shop')
                    <li class="nav-item @if(request()->is('*shop*')){{ 'active' }}@endif">
                        <a href="{{ route('shop.index') }}">
                            <i class="feather icon-shopping-cart"></i>
                            <span class="menu-title">Shop</span>
                        </a>
                    </li>
                    @endcan
                    @can('view-log')
                        <li class="nav-item @if(request()->is('*log*')){{ 'active' }}@endif">
                            <a href="{{ route('log.index') }}">
                                <i class="feather icon-archive"></i>
                                <span class="menu-title">Log</span>
                            </a>
                        </li>
                    @endcan
                    @can('view-codebook')
                    <li class=" nav-item">
                        <a href="javascript:">
                            <i class="feather icon-settings"></i>
                            <span class="menu-title">{{ trans('codebook.title') }}</span>
                        </a>
                        <ul class="menu-content">
                            @foreach(trans('codebook.vars.types') as $key => $value)
                            <li class="@if((request()->is('*code-book*')) && (request('type') == $key)){{ 'active' }}@endif">
                                <a href="{{ route('code-book.index', ['type' => $key]) }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">{{ $value }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endcan
                    @if(can('view-user') || can('view-role') || can('view-permission'))
                    <li class=" nav-item">
                        <a href="javascript:">
                            <i class="feather icon-unlock"></i>
                            <span class="menu-title">{{ trans('skeleton.acl_management') }}</span>
                        </a>
                        <ul class="menu-content">
                            @can('view-user')
                            <li class="@if(request()->is('*user*')){{ 'active' }}@endif">
                                <a href="{{ route('user.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">{{ trans('user.title') }}</span>
                                </a>
                            </li>
                            @endcan
                            @can('view-role')
                            <li class="@if(request()->is('*role*')){{ 'active' }}@endif">
                                <a href="{{ route('role.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">{{ trans('role.title') }}</span>
                                </a>
                            </li>
                            @endcan
                            @can('view-permission')
                            <li class="@if(request()->is('*permission*')){{ 'active' }}@endif">
                                <a href="{{ route('permission.index') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item">{{ trans('permission.title') }}</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        <!-- end: main menu -->
        <!-- start: content -->
        <div class="app-content content">
            <div class="content-overlay"></div>
            <div class="header-navbar-shadow"></div>
            <div class="content-wrapper">
                <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Subjekti</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text"  name="searh" class="searchSubject form-control" placeholder="Upisite subjekta">

                    <div class="search-result-subject" style="overflow: scroll;display:none;">
                        
                    </div>
                    @if(count(ScopedDocument::orders()) > 0) 
                    <hr>
                        <h4 class="text-danger">Imate nepotvrdjene narudžbe</h4>
                    <hr>
                    <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Kupac</th>
                                <th scope="col">Platitelj</th>
                                <th scope="col">Broj narudžbe</th>
                                <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(ScopedDocument::orders() as $key => $order)
                                <tr>
                                    <th scope="row" class="text-right">{{$key+1}}</th>
                                    <td class="text-right">{{$order->subject->acName2}}</td>
                                    <td class="text-right">{{$order->acPayerName}}</td>
                                    <td class="text-right"><a href="{{route('createorder', ['id' => $order->id])}}">{{$order->orderNumber}}</a></td>
                                    <td class="text-danger text-right">Nepotvrđena</td>
                                </tr>
                                @endforeach
                                
                            </tbody>
                    </table>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zatvori</button>
                </div>
                </div>
            </div>
        </div>
        <!--END MODAL -->
                @if($scoped_header)
                <div class="alert alert-info d-lg-none d-xl-none">
                    <a href="{{ route('document.show', [ScopedDocument::id()]) }}" title="{{ trans('document.actions.show') }}" data-toggle="tooltip">
                        <h6>{{ ScopedDocument::client()->full_name }}</h6>
                        <h4><strong>{{ ScopedDocument::type() }} #{{ ScopedDocument::id() }}</strong></h4>
                        <p>Ukupno: <strong data-document-subtotal>{{ format_price(ScopedDocument::totalDiscountedValue(), 2) }} {{ ScopedDocument::currency() }}</strong> | <strong data-document-total-items>{{ ScopedDocument::totalItems() }}</strong> proizvoda</p>
                    </a>
                </div>
                @endif
                @yield('content')
            </div>
        </div>
        <!-- end: content -->
        <div class="sidenav-overlay"></div>
        <div class="drag-target"></div>
        <!-- start: footer -->
        <footer class="footer footer-light @if($scoped_footer){{ 'text-right' }}@endif">
            @if($scoped_footer)
            <a href="{{ route('document.draft.complete') }}" class="btn btn-{{ ScopedDocument::typeId() }}" data-scoped-document-complete><span class="feather icon-check"></span> {{ trans('document.actions.complete.'.ScopedDocument::typeId()) }}</a>
            <a href="{{ route('document.close') }}" class="btn btn-outline-black" data-scoped-document-close><span class="feather icon-x"></span> {{ trans('document.actions.close') }}</a>
                @else
            <p class="clearfix blue-grey lighten-2 mb-0">
                <span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; {{ now()->format('Y') }}<a class="text-bold-800 grey darken-2" href="https://enabavka.ba" target="_blank" rel="noopener">enabavka.ba,</a>All rights Reserved</span><span class="float-md-right d-none d-md-block">Hand-crafted & Made with<i class="feather icon-heart pink"></i></span>
                <button class="btn btn-primary btn-icon scroll-top" type="button"><i class="feather icon-arrow-up"></i></button>
            </p>
            @endif
        </footer>
        <!-- end: footer -->
        @include('partials.modal_placeholder')
        <!-- start: loader -->
        <div id="loader"><div class="loading">loading ...</div></div>
        <!-- end: loader -->
        <!-- start: scripts -->

        <script src="{{ asset('assets/theme/vendors/js/vendors.min.js').assetVersion() }}" type="text/javascript"></script>
        @yield('script-vendor')
        <script src="{{ asset('assets/theme/js/app.min.js').assetVersion() }}" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" type="text/javascript"></script>
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

<script>
    $(document).ready(function(){
        $(".searchSubject").keyup(function(){
            if(this.value.length < 1){
                $(".search-result-subject").hide()
                $(".appended-search-subject").remove()
                return false
            }

            $(".search-result-subject").hide()
            $(".appended-search-subject").remove()
            $.ajax({
                type: "GET",
                url: location.origin+"/api/ba/subjects/search",
                data:{
                    search: this.value
                },
                success: function(res){
                    $(".appended-search-subject").remove()
                    $(".search-result-subject").css('height', '250px')
                    $(".search-result-subject").css('display', 'grid')

                    if(res.length > 0){
                        for(var x = 0;x < res.length;x++){
                            $(".search-result-subject").append("<a href="+location.origin+"/createorder?acSubject="+res[x].acSubject+" class='border appended-search-subject p1 cursor-pointer' style='color: black !important;padding:5px;'>"+res[x].acName2 + '||' +res[x].acAddress +"</a>")
                        }
                    }
                },
                error:function(error)
                {
                }
            });
        })
    });
</script>
        @include('partials.firebase')
        <!-- end: scripts -->
    </body>
</html>
