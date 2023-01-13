<div class="price-details">
    <p>Proračun</p>
</div>
<div class="detail">
    <div class="detail-title">
        Iznos bez PDV
    </div>
    <div class="detail-amt">
        {{ format_price($document->total_value) }} {{ $document->currency }}
    </div>
</div>
@if((ScopedDocument::discount1() > 0) || (ScopedDocument::discount2() > 0))
    <hr>
    @if(ScopedDocument::discount1() > 0)
        <div class="detail">
            <div class="detail-title">
                Rabat 1
            </div>
            <div class="detail-amt">
                {{ ScopedDocument::discount1() }}%
            </div>
        </div>
    @endif
    @if(ScopedDocument::discount2() > 0)
        <div class="detail">
            <div class="detail-title">
                Rabat 2
            </div>
            <div class="detail-amt">
                {{ ScopedDocument::discount2() }}%
            </div>
        </div>
    @endif
    <div class="detail">
        <div class="detail-title">
            Iznos rabata
        </div>
        <div class="detail-amt discount-amt">
            -{{ format_price($document->total_value - calculateDiscount($document->total_value, ScopedDocument::discount1(), ScopedDocument::discount2())) }} {{ $document->currency }}
        </div>
    </div>
@endif
<hr>
<div class="detail">
    <div class="detail-title">
        Iznos sa rabatom
    </div>
    <div class="detail-amt">
        {{ format_price($neto = calculateDiscount($document->total_value, ScopedDocument::discount1(), ScopedDocument::discount2())) }} {{ $document->currency }}
    </div>
</div>
<hr />
<div class="detail">
    <div class="detail-title">
        PDV ({{ $document->tax_rate }}%)
    </div>
    <div class="detail-amt">
        {{ format_price(getDiscountedValue($document->tax_rate, $neto)) }} {{ $document->currency }}
    </div>
</div>

<div class="detail">
    <div class="detail-title">
        Iznos sa PDV
    </div>
    <div class="detail-amt">
        {{ format_price($total = getDiscountedValue(100 + $document->tax_rate, $neto)) }} {{ $document->currency }}
    </div>
</div>
<hr />
<div class="detail">
    <div class="detail-title">
        Troškovi dostave
    </div>
    <div class="detail-amt">@php $delivery_cost = calcDeliveryCost($document->delivery_type, $document->rStock->country_id, $total, $document->delivery_cost) @endphp
        {{ format_price($delivery_cost) }} {{ $document->currency }}
    </div>
</div>
<hr>
<div class="detail">
    <div class="detail-title detail-total">Sve ukupno</div>
    <div class="detail-amt total-amt">{{ format_price($total + $delivery_cost) }} {{ $document->currency }}</div>
</div>
