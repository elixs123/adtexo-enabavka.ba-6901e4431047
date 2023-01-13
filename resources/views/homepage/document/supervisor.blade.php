<div class="row">
    <div class="col-12 col-lg-6">
        <ul class="nav nav-tabs" role="tablist">
            @foreach($main_tabs as $main_tab_code => $main_tab_name)
            <li class="nav-item">
                <a class="nav-link {{ ($main_tab_code == $query['tab']) ? 'active' : '' }}" href="{{ route('dashboard', httpQuery($query, ['tab' => $main_tab_code], false, ['start', 'end', 'tab'])) }}" data-loader>{{ $main_tab_name }}</a>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="col-12 col-lg-6">
        @include('homepage._dates', ['route' => 'dashboard'])
    </div>
</div>

@if($query['tab'] == 'salesmen')
<div class="row">
    @include('homepage._user_total', ['user_total' => $user_total])
    <div class="col-12">
        <div class="card card-route">
            <div class="card-header pb-1 border-bottom">
                <h4 class="card-title"><span class="badge badge-dark">{{ $dates_data['start_date']->format('d.m.Y') }}</span> - <span class="badge badge-dark">{{ $dates_data['end_date']->format('d.m.Y') }}</span>@if(request('created_by')) <a href="{{ route('dashboard', ['start' => $dates_data['start_date']->toDateString(), 'end' => $dates_data['end_date']->toDateString()]) }}" class="badge">{{ trans('skeleton.show_all') }}</a>@endif</h4>
            </div>
            <div class="card-content">
                @if(!empty($user_documents))
                <div class="table-responsive-lg">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ trans('person.data.name') }}</th>
                                <th class="text-center">{{ trans('person.data.planned') }}</th>
                                <th class="text-center">{{ trans('person.data.accomplished') }}</th>
                                <th class="text-center">{{ trans('person.data.realization') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user_documents as $person_id => $document)
                            <tr>@php $realization = ($document['preorder'] == 0) ? 0 : $document['order'] / $document['preorder']; @endphp
                                <td><a href="{{ route('document.index', ['filters' => 1, 'created_by' => $person_id, 'start_date' => $dates_data['start_date']->toDateString(), 'end_date' => $dates_data['end_date']->toDateString(), 'type_id' => 'order']) }}" title="{{ trans('person.actions.filter') }}" data-tooltip>{{ isset($user_persons[$person_id]) ? $user_persons[$person_id] : '-' }}</a></td>
                                <td class="text-center"><strong>{{ format_price($document['preorder'], 2) }}</strong> {{ ScopedStock::currency() }}</td>
                                <td class="text-center"><strong>{{ format_price($document['order'], 2) }}</strong> {{ ScopedStock::currency() }}</td>
                                <td class="{{ ($realization >= 1) ? 'bg-success' : 'bg-danger' }} text-white"><strong>{{ format_price($realization * 100) }}</strong> %</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="no-results show" data-no-results>
                    <h5>{{ trans('skeleton.no_results') }}</h5>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

@if($query['tab'] == 'express_post')
@include('homepage.document._express_post')
@endif

@section('script_inline')
    @parent
    <script>
        $(document).ready(function () {
        
        });
    </script>
@endsection
