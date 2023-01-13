<h5 class="mb-0 text-center">@php $type = 'preorder'; $doc_type = get_codebook_opts('document_type')->where('code', $type)->first()->toArray(); @endphp
    <span class="badge badge-dark">{{ trans('person.data.planned') }}</span>
    <span class="badge" style="background-color: {{ $doc_type['background_color'] }};color {{ $doc_type['color'] }};">{{ format_price($subtotal['preorder']['value']) }} {{ ScopedStock::currency() }}</span>
</h5>
<h5 class="mb-0 text-center">@php $type = 'order'; $doc_type = get_codebook_opts('document_type')->where('code', $type)->first()->toArray(); @endphp
    <span class="badge badge-dark">{{ trans('person.data.accomplished') }}</span>
    <span class="badge" style="background-color: {{ $doc_type['background_color'] }};color {{ $doc_type['color'] }};">{{ format_price($subtotal['order']['value']) }} {{ ScopedStock::currency() }}</span>
</h5>
<h5 class="mb-0 text-center">
    <span class="badge badge-dark">{{ trans('person.data.realization') }}</span>@php $realization = ($subtotal['preorder']['value'] == 0) ? 0 : $subtotal['order']['value'] / $subtotal['preorder']['value']; @endphp
    <span class="badge {{ ($realization >= 1) ? 'badge-success' : 'badge-danger' }}">{{ format_price($realization * 100) }}%</span>
</h5>
