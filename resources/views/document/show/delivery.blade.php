@if($document->delivery_type == 'personal_takeover')
<h5>Preuzeto</h5>
    @if(!is_null($takeover = $document->rTakeover))
<div class="recipient-info">
    <p><strong>{{ $takeover->name }}</strong></p>
    <p>Datum: {{ $takeover->picked_at->format('d.m.Y. H:i') }}</p>
</div>
    @endif
@else
<h5>{{ trans('document.data.delivery') }}</h5>
    @if(!is_null($document->shipping_data))
<div class="recipient-info">@php $is_location = array_get($document->buyer_data, 'is_location', false); $code = $is_location ? array_get($document->buyer_data, 'location_code', '-') : array_get($document->buyer_data, 'code', '-');  @endphp
    <p><strong class="badge badge-primary">{{ $code }}</strong> {!! array_get($document->shipping_data, 'name', '&nbsp;') !!}</p>
    <p>{!! array_get($document->shipping_data, 'address', '&nbsp;') !!}</p>
    <p>{!! array_get($document->shipping_data, 'postal_code', '&nbsp;') !!} {!! array_get($document->shipping_data, 'city', '&nbsp;') !!}, <span class="text-uppercase">{!! array_get($document->shipping_data, 'country', '&nbsp;') !!}</span></p>
    @if(!is_null($phone = array_get($document->shipping_data, 'phone')))
    <p>Kontakt: <a href="tel:{{ $phone }}">{{ $phone }}</a></p>
    @endif
    @if(!is_null($express_post = $document->rExpressPost))
    <hr>
    <p>Brza pošta: <strong>{{ $express_post->express_post_name }}</strong></p>
    <p>ID pošiljke: #{{ $express_post->shipment_id }}</p>
    <p>Tracking number: {{ $express_post->tracking_number }}</p>
    <p>Preuzeto: {{ is_null($express_post->picked_at) ? '' : $express_post->picked_at->format('d.m.Y. H:i') }}</p>
    <p>Dostavljeno: {{ is_null($express_post->delivered_at) ? '' : $express_post->delivered_at->format('d.m.Y. H:i') }}</p>
    <p>Iznos: {{ format_price($document->fiscal_discounted_price + $document->fiscal_delivery_price, 2) }} {{ $document->currency }}</p>
    @endif
</div>
    @endif
@endif
