<!--START QUICKVIEW -->
<div id="quickview" class="quickview-wrapper">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs">
        @if(($is_not_task = !(request()->is('*task*') || (request('section') == 'task'))) && can('view-task'))
        <li class="">
            <a href="#quickview-tasks" data-quick-view="{{ url('task/quick') }}">Zadaci</a>
        </li>
        @endif
        @if(($is_not_hearing = !(request()->is('*hearing*') || (request('section') == 'hearing'))) && can('view-hearing'))
        <li>
            <a href="#quickview-hearings" data-quick-view="{{ url('hearing/quick') }}">Ročišta</a>
        </li>
        @endif
        @if(($is_not_note = !(request()->is('*note*') || (request('section') == 'note'))) && can('view-note'))
        <li class="">
            <a href="#quickview-notes" data-quick-view="{{ url('note/quick') }}">Napomene</a>
        </li>
        @endif
    </ul>
    <a class="btn-link quickview-toggle" data-toggle-element="#quickview" data-toggle="quickview"><i class="pg-close"></i></a>
    <!-- Tab panes -->
    <div class="tab-content">
        @if($is_not_task)
        <!-- BEGIN Tasks !-->
        <div class="tab-pane fade in no-padding" id="quickview-tasks">
            <div class="view-port clearfix">
                <!-- BEGIN Alerts View !-->
                <div class="view bg-white">
                    <!-- BEGIN View Header !-->
                    <div class="navbar navbar-default navbar-sm">
                        <div class="navbar-inner">
                            <div class="view-heading">
                                Zadaci
                            </div>
                        </div>
                    </div>
                    <!-- END View Header !-->
                    <!-- BEGIN Alert List !-->
                    <div class="list-view boreded no-top-border">
                        <div class="quick-view-loader">
                            <img class="image-responsive-height demo-mw-50" src="{{ asset('assets/img/progress.svg') }}" alt="Progress">
                        </div>
                        <div data-quick-view-holder></div>
                    </div>
                    <!-- END Alert List !-->
                </div>
                <!-- EEND Alerts View !-->
            </div>
        </div>
        <!-- END Tasks !-->
        @endif
        @if($is_not_note)
        <!-- BEGIN Notes !-->
        <div class="tab-pane fade in no-padding" id="quickview-notes">
            <div class="view-port clearfix">
                <!-- BEGIN Note List !-->
                <div class="view bg-white">
                    <!-- BEGIN View Header !-->
                    <div class="navbar navbar-default navbar-sm">
                        <div class="navbar-inner">
                            <div class="view-heading">
                                Napomene
                            </div>
                        </div>
                    </div>
                    <!-- END View Header !-->
                    <!-- BEGIN Alert List !-->
                    <div class="list-view boreded no-top-border">
                        <div class="quick-view-loader">
                            <img class="image-responsive-height demo-mw-50" src="{{ asset('assets/img/progress.svg') }}" alt="Progress">
                        </div>
                        <div data-quick-view-holder></div>
                    </div>
                    <!-- END Alert List !-->
                </div>
                <!-- END Note List !-->
            </div>
        </div>
        @endif
        <!-- END Notes !-->
        @if($is_not_hearing)
        <!-- BEGIN Hearings !-->
        <div class="tab-pane fade in no-padding" id="quickview-hearings">
            <div class="view-port clearfix">
                <div class="view bg-white">
                    <!-- BEGIN View Header !-->
                    <div class="navbar navbar-default navbar-sm">
                        <div class="navbar-inner">
                            <div class="view-heading">
                                Ročišta
                            </div>
                        </div>
                    </div>
                    <!-- END View Header !-->
                    <div class="list-view boreded no-top-border">
                        <div class="quick-view-loader">
                            <img class="image-responsive-height demo-mw-50" src="{{ asset('assets/img/progress.svg') }}" alt="Progress">
                        </div>
                        <div data-quick-view-holder></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hearings !-->
        @endif
    </div>
</div>
<!-- END QUICKVIEW-->