<div class="price-details">
	<p>Proračun</p>
</div>

<div class="detail">
    <div class="detail-title">
        {{ trans('document.data.show_price') }}
    </div>
    <div class="detail-amt">@php $price = $document->useMpcPrice() ? $document->total : $document->subtotal; @endphp
        {{ format_price($price, 2) }} {{ $document->currency }}
    </div>
</div>
<hr>

@if($document->discount1 > 0)
<div class="detail">
    <div class="detail-title">
        Rabat 1
    </div>
    <div class="detail-amt">
        {{ format_price($document->discount1, 2) }}%
    </div>
</div>
@endif
@if($document->discount2 > 0)
<div class="detail">
    <div class="detail-title">
        Rabat 2
    </div>
    <div class="detail-amt">
        {{ format_price($document->discount2, 2) }}%
    </div>
</div>
@endif
<hr>
<div class="detail">
    <div class="detail-title">
        {{ trans('document.data.show_discount') }}
    </div>
    <div class="detail-amt discount-amt">@php $disc = $price - ($document->useMpcPrice() ? $document->total_discounted : $document->subtotal_discounted); @endphp
        -{{ format_price($disc, 2) }} {{ $document->currency }}
    </div>
</div>
@if($disc > 0)
<div class="detail">
    <div class="detail-title">
        Ukupno sa rabatom
    </div>
    <div class="detail-amt">
        {{ format_price($price - $disc, 2) }} {{ $document->currency }}
    </div>
</div>
@endif
<hr>
<div class="detail">
    <div class="detail-title">
        {{ trans('document.data.show_net') }}
    </div>
    <div class="detail-amt">@php $net_value = $document->useMpcPrice() ? getPriceWithoutVat($document->total_discounted, $document->tax_rate) : $document->subtotal_discounted; @endphp
        {{ format_price($net_value, 2) }} {{ $document->currency }}
    </div>
</div>
<hr>

<div class="detail">
    <div class="detail-title">
        {{ trans('document.data.vat', ['vat' => $document->tax_rate]) }}
    </div>
    <div class="detail-amt">@php $vat_value = getVatFromPrice($net_value, $document->tax_rate)  @endphp
        {{ format_price($vat_value, 2) }} {{ $document->currency }}
    </div>
</div>
<div class="detail">
    <div class="detail-title">
        {{ trans('document.data.show_net_tax') }}
    </div>
    <div class="detail-amt">@php $net_tax_value = $net_value + $vat_value; @endphp
        {{ format_price($net_tax_value, 2) }} {{ $document->currency }}
    </div>
</div>
<hr>

<div class="detail">
    <div class="detail-title">
        {{ trans('document.data.show_total') }}
    </div>
    <div class="detail-amt">
        {{ format_price($net_tax_value, 2) }} {{ $document->currency }}
    </div>
</div>
<hr>

<div class="detail">
    <div class="detail-title">
        Troškovi dostave
    </div>
    <div class="detail-amt">@php $delivery_cost = clientTypeDeliveryCost(calcDeliveryCost($document->delivery_type, $document->rStock->country_id, $net_value, $document->delivery_cost), $document->rClient->type_id, $document->tax_rate) @endphp
        {{ format_price($delivery_cost, 2) }} {{ $document->currency }}
    </div>
</div>
<hr>

<div class="detail">
    <div class="detail-title detail-total">Sve ukupno</div>
    <div class="detail-amt total-amt">{{ format_price($net_tax_value + $delivery_cost, 2) }} {{ $document->currency }}</div>
</div>
<hr>
