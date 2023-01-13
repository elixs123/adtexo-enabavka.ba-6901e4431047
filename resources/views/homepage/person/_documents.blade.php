@can('view-document')
<!-- start: documents -->
<div class="row">
    @include('homepage._week', ['week_data' => $week_data])
    @include('homepage._user_total', ['user_total' => $user_total])
    @foreach($week_data['days'] as $day)
    <div class="col-12">@php $date = toCarbonDate($day, 'Y-m-d'); $subtotal = ['preorder' => ['value' => 0], 'order' => ['value' => 0]]; @endphp
        <div class="card card-route">
            <div class="card-header pb-1 border-bottom">
                <h4 class="card-title"><span class="badge badge-success">{{ trans('route.vars.days')[strtolower($date->shortEnglishDayOfWeek)] }}</span> <span class="badge badge-dark">{{ $date->format('d.m.Y') }}</span></h4>
            </div>
            <div class="card-content">
                @if(isset($user_documents[$day]))
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
                            @foreach($user_documents[$day] as $person_id => $document)
                            <tr>@php $realization = ($document['preorder'] == 0) ? 0 : $document['order'] / $document['preorder']; @endphp
                                <td><a href="{{ route('document.index', ['created_by' => $person_id]) }}" title="{{ trans('document.title') }}" data-tooltip>{{ isset($user_persons[$person_id]) ? $user_persons[$person_id] : '-' }}</a></td>
                                <td class="text-center"><strong>{{ format_price($document['preorder']) }}</strong> {{ ScopedStock::currency() }}</td>@php $subtotal['preorder']['value'] += $document['preorder']; @endphp
                                <td class="text-center"><strong>{{ format_price($document['order']) }}</strong> {{ ScopedStock::currency() }}</td>@php $subtotal['order']['value'] += $document['order']; @endphp
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
            <div class="card-footer d-flex justify-content-between align-items-center">
                @include('homepage._user_subtotal', ['subtotal' => $subtotal])
            </div>
        </div>
    </div>
    @endforeach
</div>
<!-- end: documents -->
@endcan
