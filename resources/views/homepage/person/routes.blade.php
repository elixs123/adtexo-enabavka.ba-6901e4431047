<!-- start: routes -->
<div class="row">
    <div class="col-12 col-lg-6">
        <ul class="nav nav-tabs" role="tablist">
            @foreach($main_tabs as $main_tab_code => $main_tab_name)
            <li class="nav-item">
                <a class="nav-link {{ ($main_tab_code == $query['tab']) ? 'active' : '' }}" href="{{ route('dashboard', httpQuery($query, ['tab' => $main_tab_code], false, ['tab'])) }}" data-loader>{{ $main_tab_name }}</a>
            </li>
            @endforeach
        </ul>
    </div>
    @if($query['tab'] == 'routes')
    @include('homepage._week', ['week_data' => $week_data])
    @endif
</div>

@if($query['tab'] == 'routes')
<div class="row">
    @include('homepage._user_total', ['user_total' => $user_total])
    @foreach($user_routes as $key => $user_route)
    <div class="col-12">@php $subtotal = ['preorder' => ['value' => 0], 'order' => ['value' => 0]]; @endphp
        <div class="card card-route" data-route="{{ $key }}">
            <div class="card-header pb-1 border-bottom">
                <h4 class="card-title"><span class="badge badge-primary">{{ trans('route.vars.weeks')[$user_route['week']] }}</span> <span class="badge badge-success">{{ trans('route.vars.days')[$user_route['day']] }}</span> <span class="badge badge-dark">{{ $user_route['date'] }}</span></h4>
            </div>
            <div class="card-content">
                @if($count = count($user_route['data']))
                <ul class="list-group list-group-flush">
                    @foreach($user_route['data'] as $route)
                    <li id="row{{ $route['uid'] }}" class="list-group-item d-md-flex justify-content-between align-items-center">
                        <div class="mb-2 mb-md-0">
                            <strong>{{ $route['r_client']['full_name'] }}</strong><br>
                            @if($route['client_location_type'])<small class="text-info">{{ $route['client_location_type'] }}</small><small> / </small>@endif
                            @if($route['r_client']['latitude'] && $route['r_client']['longitude'])
                            <small><a href="https://www.google.com/maps/search/?api=1&query={{ $route['r_client']['latitude'] }},{{ $route['r_client']['longitude'] }}" target="_blank" rel="noopener">{{ $route['r_client']['full_address'] }}</a></small>
                                @else
                            <small>{{ $route['r_client']['full_address'] }}</small>
                            @endif
                        </div>
                        <div class="mb-2 mb-md-0">
                            @if(isset($user_documents[$user_route['date_eng']][$route['client_id']]))
                                @foreach($user_documents[$user_route['date_eng']][$route['client_id']] as $key => $documents)
                                    @foreach($documents as $document)
                            <a class="badge" href="{{ ($document['status'] == 'draft') ? route('document.open', [$document['id']]) : route('document.show', [$document['id']]) }}" style="background-color: {{ $document['background_color'] }}; color: {{ $document['color'] }};" title="{{ ($document['status'] == 'draft') ? trans('document.actions.open') : trans('document.actions.show') }}" data-tooltip @if($document['status'] == 'draft'){{ 'data-scoped-document-open' }}@endif>{{ $document['name'] }}: {{ format_price($document['value']) }} {{ $document['currency'] }} | {{ $document['status_name'] }}</a>
                            @php $subtotal[$key]['value'] += $document['value']; @endphp
                                        @endforeach
                                @endforeach
                            @endif
                        </div>
                        <div class="btn-group pull-right">
                            <div class="dropdown">
                                <button class="btn btn-flat dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    @if(can('create-document') && !ScopedDocument::exist())
                                        @foreach(trans('document.actions.new') as $key => $value)
                                    <a class="dropdown-item" href="{{ route('document.create', ['type_id' => $key, 'client_id' => $route['client_id'], 'date_of_order' => $user_route['date_eng'], 'callback' => 'documentRedirect']) }}" data-toggle="modal" data-target="#form-modal1">{{ $value }}</a>
                                        @endforeach
                                    @endif
                                    @if(can('view-document') && false)
                                    <a class="dropdown-item" href="{{ route('document.index', ['type_id' => 'order', 'client_id' => $route['client_id'], 'filters' => 1]) }}">{{ trans('document.actions.last_order') }}</a>
                                    <a class="dropdown-item" href="{{ route('document.index', ['type_id' => 'preorder', 'client_id' => $route['client_id'], 'filters' => 1]) }}">{{ trans('document.actions.last_preorder') }}</a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif
                <div class="no-results @if(!$count){{ 'show' }}@endif" data-no-results>
                    <h5>{{ trans('skeleton.no_results') }}</h5>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                @include('homepage._user_subtotal', ['subtotal' => $subtotal])
            </div>
        </div>
    </div>
    @endforeach
</div>
<!-- end: routes -->
@endif

@if($query['tab'] == 'express_post')
@include('homepage.document._express_post')
@endif
