<div class="row">
    <div class="col-12 col-lg-6">
        @include('homepage._countries', ['route' => 'dashboard'])
    </div>
    <div class="col-12 col-lg-6">
        @include('homepage._dates', ['route' => 'dashboard'])
    </div>
    <div class="col-12">
        <ul class="nav nav-tabs" role="tablist">
            @foreach($main_tabs as $main_tab_code => $main_tab_name)
            <li class="nav-item">
                <a class="nav-link {{ ($main_tab_code == $query['tab']) ? 'active' : '' }}" href="{{ route('dashboard', httpQuery($query, ['tab' => $main_tab_code], false, ['start', 'end', 'country', 'tab'])) }}" data-loader>{{ $main_tab_name }}</a>
            </li>
            @endforeach
        </ul>
    </div>
</div>

@if($query['tab'] == 'salesmen')
<!-- start: persons - salesman -->
<div class="row">
    @include('homepage._user_total', ['user_total' => $user_total])
    <div id="salesmans" class="col-12">
        <div class="card card-route">
            <div class="card-content">
                @if(!empty($person_documents))
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
                            @foreach($person_documents as $person_id => $document)
                            <tr>@php $realization = ($document['preorder'] == 0) ? 0 : $document['order'] / $document['preorder']; @endphp
                                <td><a href="{{ route('document.index', ['filters' => 1, 'created_by' => $person_id, 'start_date' => $dates_data['start_date']->toDateString(), 'end_date' => $dates_data['end_date']->toDateString()]) }}" title="{{ trans('person.actions.filter') }}" data-tooltip>{{ isset($user_persons[$person_id]) ? $user_persons[$person_id] : '-' }}</a></td>
                                <td class="text-center"><strong>{{ format_price($document['preorder'], 2) }}</strong> {{ $currency }}</td>
                                <td class="text-center"><strong>{{ format_price($document['order'], 2) }}</strong> {{ $currency }}</td>
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
<!-- end: persons - salesman -->
@endif

@if($query['tab'] == 'documents')
<!-- start: documents -->
<div id="documents" class="row">
    <div class="col-12">
        <ul class="nav nav-tabs mb-0" role="tablist">
            @foreach(get_codebook_opts('document_type')->sortBy('id') as $document_type)
            <li class="nav-item">
                <a class="nav-link {{ ($document_type->code == $query['type_id']) ? 'active' : '' }}" href="{{ route('dashboard', httpQuery($query, ['type_id' => $document_type->code])) }}#documents" data-loader>{{ $document_type->name }}</a>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="col-12">
        <ul class="nav nav-tabs" role="tablist">
            @foreach(getDocumentStatusSorted() as $status_code => $status)
            <li class="nav-item">
                <a class="nav-link {{ ($status_code == $query['status']) ? 'active' : '' }}" href="{{ route('dashboard', httpQuery($query, ['status' => $status_code])) }}#documents" data-loader>{{ $status->name }}</a>
            </li>
            @endforeach
        </ul>
    </div>
    @php $doc_status = get_codebook_opts('document_status')->where('code', $query['status'])->first(); $doc_type = get_codebook_opts('document_type')->where('code', $query['type_id'])->first(); @endphp
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-1 border-bottom">
                <h4 class="card-title"><span class="badge" style="background-color: {{ $doc_type->background_color }};color: {{ $doc_type->color }};">{{ $doc_type->name }}</span> <span class="badge" style="background-color: {{ $doc_status->background_color }};color: {{ $doc_status->color }};">{{ $doc_status->name }}</span> <span class="badge badge-dark">{{ $user_documents->count() }}</span></h4>
            </div>
            <div class="card-content">
                @if(!empty($user_documents))
                <div class="table-responsive-lg">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>{{ trans('document.data.date_of_order') }}</th>
                                <th>{{ trans('document.data.client_id') }}</th>
                                @if($query['type_id'] != 'return')
                                <th>{{ trans('document.data.payment_type') }}</th>
                                @endif
                                <th style="width: 250px;">{{ trans('document.data.note') }}</th>
                                <th class="text-right">{{ trans('document.data.subtotal') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user_documents as $document)
                            <tr>
                                <td>{{ $document->id }}</td>
                                <td>{{ $document->date_of_order->format('d.m.Y.') }}</td>
                                <td>
                                    @if(is_null($document->client_id))
                                    <span>-</span>
                                    @else
                                    <a href="{{ route('document.show', ['id' => $document->id]) }}" title="{{ trans('document.actions.show') }}" data-tooltip><strong>{{ $document->rClient->full_name }}</strong></a>
                                    @if(!is_null($document->rClient->rSalesmanPerson))
                                    <br><small>({{ $document->rClient->rSalesmanPerson->name }})</small>
                                    @endif
                                    @endif
                                </td>
                                @if($query['type_id'] != 'return')
                                <td>
                                    <small class="badge" style="background-color: {{ $document->rPaymentType->background_color }};color: {{ $document->rPaymentType->color }};">{{ $document->rPaymentType->name }}</small>
                                </td>
                                @endif
                                <td>
                                    @if(is_null($document->note))
                                    <small>-</small>
                                    @else
                                    <small>{{ str_limit($document->note, 50) }}</small>@if(strlen($document->note) > 50) <span class="fa fa-plus-square text-primary cursor-pointer" data-toggle="tooltip" data-title="{!! nl2br($document->note) !!}" data-html="true"></span>@endif
                                    @endif
                                </td>
                                <td class="text-right"><strong>{{ format_price($document->total_discounted_value, 2) }}</strong> {{ $document->currency }}</td>
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
<!-- end: documents -->
@endif

@if($query['tab'] == 'express_post')
@include('homepage.document._express_post')
@endif

@if($query['tab'] == 'personal_takeover')
<!-- start: documents -->
<div id="documents" class="row">
    @php $doc_status = get_codebook_opts('document_status')->where('code', $query['status'])->first(); $doc_type = get_codebook_opts('document_type')->where('code', $query['type_id'])->first(); @endphp
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-1 border-bottom">
                <h4 class="card-title"><span class="badge" style="background-color: {{ $doc_type->background_color }};color: {{ $doc_type->color }};">{{ $doc_type->name }}</span> <span class="badge" style="background-color: {{ $doc_status->background_color }};color: {{ $doc_status->color }};">{{ $doc_status->name }}</span> <span class="badge badge-dark">{{ $user_documents->count() }}</span></h4>
            </div>
            <div class="card-content">
                @if(!empty($user_documents))
                    <div class="table-responsive-lg">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ trans('document.data.date_of_order') }}</th>
                                    <th>{{ trans('document.data.client_id') }}</th>
                                    <th class="text-right">{{ trans('document.data.subtotal') }}</th>
                                    <th>{{ trans('document.data.delivery_type') }}</th>
                                    <th>{{ trans('document.data.picked_at') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user_documents as $document)
                                <tr>
                                    <td>{{ $document->id }}</td>
                                    <td>{{ $document->date_of_order->format('d.m.Y.') }}</td>
                                    <td>
                                        @if(is_null($document->client_id))
                                        <span>-</span>
                                        @else
                                        <a href="{{ route('document.show', ['id' => $document->id]) }}" title="{{ trans('document.actions.show') }}" data-tooltip><strong>{{ $document->rClient->full_name }}</strong></a>
                                            @if(!is_null($document->rClient->rSalesmanPerson))
                                        <br><small>({{ $document->rClient->rSalesmanPerson->name }})</small>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-right"><strong>{{ format_price($document->total_discounted_value, 2) }}</strong> {{ $document->currency }}</td>
                                    <td>{{ $document->rDeliveryType->name }}</td>
                                    <td>{{ $document->rTakeover->picked_at->format('d.m.Y \u H:i') }}<br>{{ $document->rTakeover->name }}</td>
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
<!-- end: documents -->
@endif

@if($query['tab'] == 'payments')
<!-- start: payments -->
<div id="payments" class="row">
    <div class="col-12">@php $currency = $currency ?? ScopedStock::currency(); @endphp
        <div class="card bg-light">
            <div class="card-header pb-1 border-bottom d-flex justify-content-between align-items-center">
                <h3 class="mb-0 text-center">
                    <span class="badge badge-dark">Promet</span>
                    <span class="badge badge-info">{{ format_price($payment_totals['total'], 2) }} {{ $currency }}</span>
                </h3>
                <h3 class="mb-0 text-center">
                    <span class="badge badge-dark">Plaćeno</span>
                    <span class="badge badge-success">{{ format_price($payment_totals['payed'], 2) }} {{ $currency }}</span>
                </h3>
                <h3 class="mb-0 text-center">
                    <span class="badge badge-dark">Neplaćeno</span>
                    <span class="badge badge-danger">{{ format_price($payment_totals['unpaid'], 2) }} {{ $currency }}</span>
                </h3>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-content">
                @if(!empty($user_documents))
                <div class="table-responsive-lg">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>{{ trans('document.data.delivery_type') }}</th>
                                <th>Brza pošta</th>
                                <th class="text-right">Iznos</th>
                                <th class="text-right">Plaćeno</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user_documents as $document)
                            <tr>
                                <td class="text-white {{ $document->is_payed ? 'bg-success' : 'bg-danger' }}"><a href="{{ route('document.show', ['id' => $document->id]) }}" target="_blank" style="color: #ffffff;">{{ $document->id }}</a></td>
                                <td>{{ $document->rDeliveryType->name }}</td>
                                <td>{{ is_null($document->rExpressPost) ? '-' : $document->rExpressPost->express_post_name }}</td>
                                <td class="text-right">{{ format_price($document->total_discounted + $document->delivery_cost, 2) }} {{ $document->currency }}</td>
                                <td class="text-right">{{ format_price(is_null($document->rPaymentItem) ? 0 : $document->rPaymentItem->amount, 2) }} {{ $document->currency }}</td>
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
<!-- end: payments -->
@endif

@section('script_inline')
    @parent
    <script>
        $(document).ready(function () {
            if ($("[data-card-scrollbar]").length) {
                new PerfectScrollbar("[data-card-scrollbar]", {
                    suppressScrollX: true,
                    wheelPropagation: false
                });
            }
        });
    </script>
@endsection
