@extends('layouts.app')

@section('head_title', 'Notifikacije')

@section('content')
    <!-- START PAGE CONTENT WRAPPER -->
    <div class="page-content-wrapper">
        <!-- START PAGE CONTENT -->
        <div class="content">
            <div class="container-fluid container-fixed-lg">
                <ul class="breadcrumb">
                    <li><a href="{{ url('/') }}">Naslovnica</a></li>
                    <li><a href="{{ url('/notification') }}" class="active">Notifikacije</a></li>
                </ul>
            </div>
            <!-- START CONTAINER FLUID -->
            <div class="container-fluid container-fixed-lg bg-white">
                <!-- START PANEL -->
                <div class="panel panel-transparent">
                    <div class="panel-heading">
                        <div class="panel-title">Notifikacije <span class="badge badge-info" data-row-count>{{ $items->count() }}</span></div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body p-t-30" data-notification-panel>
                        <div style="margin: 0 -10px;">
                            <div class="notification-grid clearfix">
                                <div class="notification-grid-sizer"></div>
                                @foreach($items as $id => $item)
                                <div class="notification-grid-item">
                                    <div class="notification-card">
                                        <div class="card share full-width @if($item->unread()){{ 'unread' }}@endif" data-notification data-id="n{{ $item->id }}" data-status="@if($item->unread()){{ 'unread' }}@else{{ 'read' }}@endif" data-social="item">
                                            <div class="card-header clearfix">
                                                <a href="{{ route('notification.toggle', ['id' => $item->id]) }}" class="circle" data-notification-toggle></a>
                                                <div class="user-pic">
                                                    <i class="fa {{ get_notification_icon($item->data['type']) }} text-success"></i>
                                                </div>
                                                <h5><a href="{{ ($item->data['url']) ? route('notification.show', ['id' => $item->id]) : 'javascript:;' }}">{{ $item->data['title'] }}</a></h5>
                                                <h6>
                                                    <time class="location semi-bold" title="{{ $item->created_at->format('d.m.Y \u H:i') }}"><i class="fa fa-calendar"></i> {{ $item->created_at->diffForHumans() }}</time>
                                                </h6>
                                            </div>
                                            <div class="card-description">
                                                <p>{!! trans($item->data['trans_id'], $item->data[$item->data['type']]) !!}</p>
                                                <div class="via">via <strong>{{ isset($item->data['reminder']) ? title_case($item->data['reminder']['type']) : 'Notification' }}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        {!! $items->appends(request()->query())->render() !!}
                    </div>
                </div>
                <!-- END PANEL -->
            </div>
            <!-- END CONTAINER FLUID -->
        </div>
        <!-- END PAGE CONTENT -->
    </div>
@endsection

@section('script_inline')
    <script src="{{ asset('assets/admin/assets/plugins/masonry/masonry.pkgd.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.notification-grid').masonry({
                // options
                columnWidth: '.notification-grid-sizer',
                itemSelector: '.notification-grid-item',
                percentPosition: true
            });
        });
    </script>
@endsection
