<!-- START NOTIFICATION LIST -->
@php $notifications = auth()->user()->notifications()->take(15)->get(); @endphp
<ul class="notification-list no-margin b-grey b-l b-r no-style">
    <li class="p-r-15 inline">
        <div class="dropdown" data-notifications data-num="{{ $count = count($notifications->filter(function($item, $key) {return $item->unread();})) }}">
            <a href="javascript:;" id="notification-center" data-toggle="dropdown">
                <i class="fa fa-globe"></i>
                <span class="badge badge-danger" data-notification-num>{{ $count }}</span>
            </a>
            <div class="dropdown-menu notification-toggle" role="menu" aria-labelledby="notification-center">
                <div class="notification-panel" data-notification-panel>
                    <div class="notification-body scrollable" data-notification-wrapper>
                        @foreach($notifications as $notification)
                        <div class="notification-item @if($notification->unread()){{ 'unread' }}@endif clearfix" data-notification data-id="n{{ $notification->id }}" data-status="@if($notification->unread()){{ 'unread' }}@else{{ 'read' }}@endif">
                            <!-- START Notification Item-->
                            <div class="heading open">@php $href = ($notification->data['url']) ? route('notification.show', ['id' => $notification->id]) : 'javascript:;'; @endphp
                                <a href="{{ $href }}" class="pull-left">
                                    <i class="fa {{ get_notification_icon($notification->data['type']) }} fs-16 m-r-10 text-success"></i>
                                    <span class="bold">{{ $notification->data['title'] }}</span>
                                </a>
                                <div class="pull-right">
                                    <div class="thumbnail-wrapper d16 circular inline m-t-15 m-r-10 toggle-more-details">
                                        <div><i class="fa fa-angle-left"></i>
                                        </div>
                                    </div>
                                    <span class=" time">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="more-details">
                                    <div class="more-details-inner">
                                        <h5 class="semi-bold fs-14">{!! trans($notification->data['trans_id'], $notification->data[$notification->data['type']]) !!}</h5>
                                    </div>
                                </div>
                            </div>
                            <!-- END Notification Item-->
                            <!-- START Notification Item Right Side-->
                            <a href="{{ route('notification.toggle', ['id' => $notification->id]) }}" class="option" data-notification-toggle>
                                <span class="mark" ></span>
                            </a>
                            <!-- END Notification Item Right Side-->
                        </div>
                        @endforeach
                    </div>
                    <div class="notification-footer text-center">
                        <a href="{{ route('notification.index') }}" class="">Sve notifikacije</a>
                        <a class="portlet-refresh text-black pull-right" href="{{ route('notification.read') }}" data-toggle="tooltip" data-placement="left" title="Označi sve kao pročitane" data-notification-all-read>
                            <i class="pg-refresh_new"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </li>
    <li class="p-r-15 inline">
        <div class="dropdown" data-members data-num="0">
            <a href="javascript:;" id="online-users" data-toggle="dropdown">
                <i class="fa fa-users"></i>
                <span class="badge badge-success" data-members-num></span>
            </a>
            <div class="dropdown-menu notification-toggle" role="menu" aria-labelledby="online-users">
                <div class="notification-panel">
                    <div class="notification-body scrollable" data-members-content></div>
                    <div class="notification-footer text-center">
                        <a href="javascript:;" class="">Online korisnici</a>
                    </div>
                </div>
            </div>
        </div>
    </li>
</ul>
<!-- END NOTIFICATIONS LIST -->