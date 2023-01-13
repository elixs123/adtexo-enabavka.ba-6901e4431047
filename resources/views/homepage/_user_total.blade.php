<!-- start: total --> @php $currency = isset($currency) ? $currency : ScopedStock::currency(); @endphp
<div class="col-12">
    <div class="card bg-light">
        <div class="card-header pb-1 border-bottom d-flex justify-content-between align-items-center">
            <h3 class="mb-0 text-center">@php $type = 'preorder'; $doc_type = get_codebook_opts('document_type')->where('code', $type)->first()->toArray(); @endphp
                <span class="badge badge-dark">{{ trans('person.data.planned') }}</span>
                <span class="badge" style="background-color: {{ $doc_type['background_color'] }};color {{ $doc_type['color'] }};">{{ format_price($user_total[$type], 2) }} {{ $currency }}</span>
            </h3>
            <h3 class="mb-0 text-center">@php $type = 'order'; $doc_type = get_codebook_opts('document_type')->where('code', $type)->first()->toArray(); @endphp
                <span class="badge badge-dark">{{ trans('person.data.accomplished') }}</span>
                <span class="badge" style="background-color: {{ $doc_type['background_color'] }};color {{ $doc_type['color'] }};">{{ format_price($user_total[$type], 2) }} {{ $currency }}</span>
            </h3>
            <h3 class="mb-0 text-center">
                <span class="badge badge-dark">{{ trans('person.data.realization') }}</span>@php $realization = ($user_total['preorder'] == 0) ? 0 : $user_total['order'] / $user_total['preorder']; @endphp
                <span class="badge {{ ($realization >= 1) ? 'badge-success' : 'badge-danger' }}">{{ format_price($realization * 100) }}%</span>
            </h3>
        </div>
    </div>
</div>
<!-- end: total -->
