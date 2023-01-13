<div class="mustache" data-template="notification">
    <div class="notification-item @{{ class }} clearfix" data-notification data-id="n@{{ id }}" data-status="@{{ status }}">
        <!-- START Notification Item-->
        <div class="heading open">
            <a href="@{{ route_show }}" class="pull-left">
                <i class="fa @{{ icon }} fs-16 m-r-10 text-success"></i>
                <span class="bold">@{{ title }}</span>
            </a>
            <div class="pull-right">
                <div class="thumbnail-wrapper d16 circular inline m-t-15 m-r-10 toggle-more-details">
                    <div><i class="fa fa-angle-left"></i>
                    </div>
                </div>
                <span class=" time">@{{ time }}</span>
            </div>
            <div class="more-details">
                <div class="more-details-inner">
                    <h5 class="semi-bold fs-14">@{{{ message }}}</h5>
                </div>
            </div>
        </div>
        <!-- END Notification Item-->
        <!-- START Notification Item Right Side-->
        <a href="@{{ route_toggle }}" class="option" data-notification-toggle>
            <span class="mark" ></span>
        </a>
        <!-- END Notification Item Right Side-->
    </div>
</div>