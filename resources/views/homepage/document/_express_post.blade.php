<!-- start: express post -->
<div id="express_post" class="row">
    <div class="col-12">
        <ul class="nav nav-tabs" role="tablist">
            @foreach($express_post_statuses as $status_code => $status_name)
            <li class="nav-item">
                <a class="nav-link {{ ($status_code == $query['status']) ? 'active' : '' }}" href="{{ route('dashboard', httpQuery($query, ['status' => $status_code])) }}#express_post" data-loader>{{ $status_name }}</a>
            </li>
            @endforeach
        </ul>
    </div>@php $doc_status = get_codebook_opts('document_status')->where('code', $query['status'])->first(); $doc_type = get_codebook_opts('document_type')->where('code', 'order')->first(); @endphp
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-1 border-bottom">
                <h4 class="card-title"><span class="badge" style="background-color: {{ $doc_type->background_color }};color: {{ $doc_type->color }};">{{ $doc_type->name }}</span> <span class="badge" style="background-color: {{ $doc_status->background_color }};color: {{ $doc_status->color }};">{{ $doc_status->name }}</span> <span class="badge badge-dark">{{ $express_post_documents->count() }}</span></h4>
            </div>
            <div class="card-content">
                @if(!empty($express_post_documents))
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
                                <th>{{ trans('document.data.delivered_at') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($express_post_documents as $document)
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
                                <td>{{ is_null($document->rExpressPost) ? '-' : $document->rExpressPost->express_post_name }}</td>
                                <td>{{ is_null($document->rExpressPost->picked_at) ? '-' : $document->rExpressPost->picked_at->format('d.m.Y. H:i') }}</td>
                                <td>{{ is_null($document->rExpressPost->delivered_at) ? '-' : $document->rExpressPost->delivered_at->format('d.m.Y. H:i') }}</td>
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
<!-- end: express post -->
