@extends('layouts.app', [
'scoped_header' => (ScopedDocument::id() == $document->id),
'scoped_footer' => (ScopedDocument::id() == $document->id),
])

@section('head_title', $title = trans('skeleton.invoicing'))

@section('content')
<!-- start: document -->
<section id="document-print" class="card invoice-page">
    <div class="card-body">
        <!-- start: company details -->
        <div class="row">
            <div class="col-sm-6 col-12 text-left">
                <h1>{{ $document->rType->name }}</h1>
            </div>
            <div class="col-sm-6 col-12 text-right">
                <img src="{{ asset('assets/img/adtexo_logo_20201231.png').assetVersion() }}" alt="Adtexo d.o.o." />
            </div>
        </div>
        <!-- end: company details -->
        <!-- start: client details -->
        <div class="row pt-4">
            <div class="col-sm-4 col-12 text-left">
                <h5>Broj #<strong class="text-dark">{{ $document->id }}/{{ now()->format('Y') }}</strong></h5>
                <div class="recipient-info pb-2">
                    <p>Datum dokumenta: <strong class="text-dark">{{ $document->date_of_order->format('d.m.Y') }}</strong></p>
                    @if(!is_null($createdBy = $document->rCreatedBy))
                    <p>Kreiran: <strong class="text-dark">{{ $document->created_at->format('d.m.Y') }}</strong></p>
                    <p><strong class="text-dark">{{ is_null($person = $createdBy->rPerson) ? $createdBy->email : $person->name }}</strong></p>
                    @if(!is_null($person) && isset($person->rType))
                    <p>{{ (array_get($document->buyer_data, 'type_id', 'private_client') == 'private_client') ? 'Agent prodaje' : $person->rType->name }} | <a href="tel:{{ $person->phone }}">{{ $person->phone }}</a></p>
                    @endif
                    @endif
                    @if(!is_null($document->date_of_proccesing))
                    <p>Obrada: <strong class="text-dark">{{ $document->date_of_proccesing->format('d.m.Y H:i') }}</strong></p>
                    @endif
                    @if(!is_null($document->date_of_warehouse))
                    <p>Skladište: <strong class="text-dark">{{ $document->date_of_warehouse->format('d.m.Y H:i') }}</strong></p>
                    @endif
                    @if(!is_null($document->date_of_delivery))
                    <p>Isporuka: <strong class="text-dark">{{ $document->date_of_delivery->format('d.m.Y') }}</strong></p>
                    @endif
                    @if(!is_null($document->date_of_payment))
                    <p>Plaćanje: <strong class="text-dark">{{ $document->date_of_payment->format('d.m.Y') }}</strong></p>
                    @endif
                    <p>{{ trans('document.data.payment_type') }}: <strong class="text-dark">{{ $document->rPaymentType->name }}</strong></p>
                    <p>{{ trans('document.data.delivery_type') }}: <strong class="text-dark">{{ $document->rDeliveryType->name }}</strong></p>
                    @if($document->type_id == 'order')
                    <p>Valuta plaćanja: <strong class="text-dark">{{ $document->rCurrency->name }}</strong></p>
                    @endif
                    @if($document->fiscal_receipt_no != null)
                        <p>Broj fiskalnog računa: {{ $document->fiscal_receipt_no }}</p>
                        <p>Datum fiskalnog računa: {{ $document->fiscal_receipt_datetime->format('d.m.Y. H:i:s') }}</p>
                        <p>Iznos fiskalnog računa: {{ $document->fiscal_receipt_amount }}</p>
                    @endif
                </div>
            </div>
            <div class="col-sm-4 col-12 text-left">
                <h5>{{ trans('document.data.client_id') }}</h5>
                @if(!empty($document->buyer_data))
                <div class="recipient-info">
                    <p>{!! array_get($document->buyer_data, 'full_name', '&nbsp;') !!} (<strong>{!! array_get($document->buyer_data, 'name', '&nbsp;') !!}</strong>)</p>
                    <p>{!! array_get($document->buyer_data, 'address', '&nbsp;') !!}</p>
                    <p>{!! array_get($document->buyer_data, 'postal_code', '&nbsp;') !!} {!! array_get($document->buyer_data, 'city', '&nbsp;') !!}, <span class="text-uppercase">{!! array_get($document->buyer_data, 'country_id', '&nbsp;') !!}</span></p>
                </div>
                <div class="recipient-contact pb-2">
                    <p>JIB: {!! array_get($document->buyer_data, 'jib', '&nbsp;') !!}</p>
                    @if(!is_null($pib = array_get($document->buyer_data, 'pib')))
                    <p>PIB: {{ $pib }}</p>
                    @endif
                </div>
                @endif
            </div>
            @if($document->delivery_type != 'personal_takeover')
            <div class="col-sm-4 col-12">
                <h5>{{ trans('document.data.delivery') }}</h5>
                @if(!is_null($document->shipping_data))
                <div class="recipient-info">@php $is_location = array_get($document->buyer_data, 'is_location', false); $code = $is_location ? array_get($document->buyer_data, 'location_code', '-') : array_get($document->buyer_data, 'code', '-'); @endphp
                    <p><strong>{{ $code }}</strong></p>
                    <p>{!! array_get($document->shipping_data, 'name', '&nbsp;') !!}</p>
                    <p>{!! array_get($document->shipping_data, 'address', '&nbsp;') !!}</p>
                    <p>{!! array_get($document->shipping_data, 'postal_code', '&nbsp;') !!} {!! array_get($document->shipping_data, 'city', '&nbsp;') !!}, <span class="text-uppercase">{!! array_get($document->shipping_data, 'country', '&nbsp;') !!}</span></p>
                    @if(!is_null($phone = array_get($document->shipping_data, 'phone')))
                        <p>Kontakt: <a href="tel:{{ $phone }}">{{ $phone }}</a></p>
                    @endif
                </div>
                @endif
            </div>
            @endif
        </div>
        <!-- end: client details -->
        <!-- start: document items -->
        <div class="pt-1 invoice-items-table">
            <div class="row">
                {!! Form::open(['url' => route('document.changes.store', ['id' => $document->id]), 'method' => 'post', 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-document-products').' table-responsive col-12', 'novalidate']) !!}
                <table class="table table-hover mb-2 table-bordered">
                    <thead class="thead-light">
                        <tr class="text-uppercase text-right">
                            <th class="text-left">{{ trans('document.data.item') }}</th>
                            <th>{{ trans('document.data.unit_id') }}</th>
                            <th>{{ trans('document.data.quantity') }}</th>
                            <th>{{ trans('document.data.show_price') }}</th>
                            <th>{{ trans('document.data.show_discount') }}</th>
                            <th>{{ trans('document.data.show_net') }}</th>
                            <th>{{ trans('document.data.vat', ['vat' => $document->tax_rate]) }}</th>
                            <th>{{ trans('document.data.show_net_tax') }}</th>
                            <th>{{ trans('document.data.show_total') }}</th>
                        </tr>
                    </thead>
                    <tbody data-document-products>@php $qty = $val = $net = $net_tax = 0; @endphp
                        @foreach($products as $item)
                        <tr class="text-right">
                            <td class="text-left">
                                <small>{{ $item->code }}</small>
                                <br><strong>{{ $item->name }}</strong>

                                @if(isset($changes[$item->product_id]))
                                    <a href="{{ route('document.changes.index', ['id' => $document->id, 'product_id' => $item->product_id]) }}" data-toggle="modal" data-target="#form-modal1"><span class="feather icon-alert-triangle text-danger" title="{{ trans('document.data.changes') }}" data-tooltip></span></a>
                                @endif

                                <br><small>{{ $item->barcode }}</small>

                                @if(!is_null($item->contract_id))
                                    <small class="badge badge-info text-uppercase">Ugovoreni lager</small>
                                @endif
                            </td>

                            <td>{{ $item->unit_id }}</td>

                            <td>
                                @if(($document->type_id == 'order')
                                    && in_array($document->status, ['in_process', 'in_warehouse'])
                                    && (userIsEditor()
                                    || userIsWarehouse()))
                                    <input type="number" class="form-control text-center" name="product[{{ $item->id }}]" value="{{ $item->qty }}" data-plugin-mask data-plugin-options='{"mask": "######0", "placeholder": "{{ $item->qty }}", "selectOnFocus": true}'>
                                @else
                                    {{ $item->qty }}
                                @endif
                            </td>
                                @php
                                    $qty += $item->qty; // getPriceWithoutVat($item->mpc, $document->tax_rate)
                                @endphp
                                @php
                                    $price = $document->useMpcPrice()
                                    ? $item->mpc
                                    : $item->vpc;
                                @endphp

                            <td>{{ format_price($price) }}</td>
                                @php
                                    $disc = ($document->has_discount)
                                    ? ($price - calculateDiscount($price, $document->discount1, $document->discount2))
                                    : 0;
                                @endphp

                            <td>{{ format_price($disc) }}</td>
                                @php
                                    $net_value = $document->useMpcPrice()
                                    ? getPriceWithoutVat($price - $disc, $document->tax_rate)
                                    : $price - $disc;
                                    $net += $net_value;
                                @endphp

                            <td>{{ format_price($net_value) }}</td>
                                @php
                                    $vat_value = getVatFromPrice($net_value, $document->tax_rate)
                                @endphp

                            <td>{{ format_price($vat_value) }}</td>
                                @php
                                    $net_tax_value = $net_value + $vat_value;
                                @endphp

                            <td>{{ format_price($net_tax_value) }}</td>
                                @php
                                    $net_tax_value = $item->qty * $net_tax_value
                                @endphp

                            <td><strong>{{ format_price($net_tax_value) }}</strong></td>
                                @php
                                    $net_tax += $net_tax_value;
                                @endphp
                        </tr>
                        @endforeach
                        @if($products->count())
                        <tr class="table-active text-right text-bold-600">
                            <td>&nbsp;</td>
                            <td>Ukupno</td>
                            <td>{{ $qty }}</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>{{ format_price($net) }}</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><strong>{{ format_price($net_tax) }}</strong></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                @if(($document->type_id == 'order') && in_array($document->status, ['in_process', 'in_warehouse']) && (userIsEditor() || userIsWarehouse()))
                @if(userIsWarehouse())
                <div class="row">
                    <div class="col-12 col-md-6">
                        {!! VuexyAdmin::text('package_number', $document->package_number, ['required'], trans('document.data.package_number')) !!}
                    </div>
                    <div class="col-12 col-md-6">
                        {!! VuexyAdmin::text('weight', $document->weight, [], trans('document.data.weight')) !!}
                    </div>
                </div>
                @endif
                <div class="mb-2 text-right">
                    @if(userIsEditor() || userIsWarehouse())
                    <a href="{{ route('document.product.show', ['id' => $document->id, 'e' => implode('.', $products->pluck('product_id')->toArray())]) }}" class="btn btn-info" data-toggle="modal" data-target="#form-modal1">{{ trans('document.actions.add.product') }}</a>
                    @endif
                    <button type="submit" class="btn btn-success" data-document-products-change>{{ trans('skeleton.actions.save') }}</button>
                </div>
                @endif
                {!! Form::close() !!}
                <div class="no-results @if($products->count() == 0){{ 'show' }}@endif">
                    <h5>{{ trans('skeleton.no_results') }}</h5>
                </div>
            </div>
        </div>
        <!-- end: document items -->
        <!-- start: document total -->
        <div class="invoice-total-table">
            <div class="row">
                <div class="col-12 col-md-6 mb-2">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>@php $price_for_discount = $document->useMpcPrice() ? $document->total : $document->subtotal; @endphp
                                <tr>@php $discount_value1 = getDiscountedValue($document->discount1, $price_for_discount); $discounted = ($discount_value1 > 0); @endphp
                                    <th class="text-uppercase">{{ trans('document.data.discount_num', ['num' => 1, 'discount' => $document->discount1]) }}</th>
                                    <td class="text-right @if($discounted){{ 'text-success' }}@endif">@if($discounted){{ '- ' }}@endif{{ format_price($discount_value1) }} {{ $document->currency }}</td>
                                </tr>
                                <tr>@php $discount_value2 = getDiscountedValue($document->discount2, $price_for_discount - $discount_value1); $discounted = ($discount_value2 > 0); @endphp
                                    <th class="text-uppercase">{{ trans('document.data.discount_num', ['num' => 2, 'discount' => $document->discount2]) }}</th>
                                    <td class="text-right @if($discounted){{ 'text-success' }}@endif">@if($discounted){{ '- ' }}@endif{{ format_price($discount_value2) }} {{ $document->currency }}</td>
                                </tr>
                                <tr>@php $discount_total = $discount_value1 + $discount_value2; @endphp
                                    <th class="text-uppercase">{{ trans('document.data.total_discount') }}</th>
                                    <td class="text-right @if($discount_total){{ 'text-success' }}@endif"><strong>@if($discount_total){{ '- ' }}@endif{{ format_price($discount_total) }} {{ $document->currency }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12 col-md-6 mb-2">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>@php $price = getPriceWithoutVat($net_tax, $document->tax_rate); @endphp
                                    <th class="text-uppercase">{{ trans('document.data.total_no_vat') }}</th>
                                    <td class="text-right"><strong>{{ format_price($price) }} {{ $document->currency }}</strong></td>
                                </tr>
                                <tr>@php $vat = getVatFromPrice($price, $document->tax_rate); @endphp
                                    <th class="text-uppercase">{{ trans('document.data.vat', ['vat' => $document->tax_rate]) }}</th>
                                    <td class="text-right">{{ format_price($vat) }} {{ $document->currency }}</td>
                                </tr>
                                <tr>@php $price_with_vat = $net_tax; @endphp
                                    <th class="text-uppercase">{{ trans('document.data.total_with_vat') }}</th>
                                    <td class="text-right">{{ format_price($price_with_vat) }} {{ $document->currency }}</td>
                                </tr>
                                <tr> @php $delivery_cost = clientTypeDeliveryCost($document->delivery_cost, $document->rClient->type_id, $document->tax_rate); @endphp
                                    <th class="text-uppercase">{{ trans('document.data.delivery') }}</th>
                                    <td class="text-right">{{ format_price($delivery_cost) }} {{ $document->currency }}</td>
                                </tr>
                                <tr>
                                    <th class="text-uppercase">{{ trans('document.data.total') }}</th>
                                    <td class="text-right"><strong>{{ format_price($price_with_vat + $delivery_cost) }} {{ $document->currency }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end: document total -->

        @if(!is_null($document->package_number))
        <!-- start: package number & weight -->
        <p>{{ trans('document.data.package_number') }}: <strong>{{ $document->package_number }}</strong></p>
        @if(!is_null($document->weight))
        <p>{{ trans('document.data.weight') }}: <strong>{{ $document->weight }}</strong></p>
        @endif
        <!-- end: package number & weight -->
        @endif
        @if(!userIsClient() && !userIsWarehouse() && !is_null($document->rParent) && (!$document->rParent->isCash()) && ($document->rParent->subtotal > 0))
        <!-- start: preorder summary -->@php $parent = $document->rParent; @endphp
        <div class="invoice-total-table p-1 bg-{{ $diff_color = $document->getDifferenceColor() }} bg-lighten-5">
            <div class="row">
                <div class="col-12 col-md-6">
                    <h4>{{ $parent->rType->name }} #{{ $parent->id }} <span class="feather {{ $document->getDifferenceIcon() }} bg-{{ $diff_color }} text-white"></span></h4>
                    <p>{{ trans('document.data.date_of_order') }}: <strong>{{ $parent->date_of_order->format('d.m.Y') }}</strong></p>
                </div>
            </div>
        </div>
        <!-- end: preorder summary -->
        @endif
        @if(false)
        <!-- start: document footer -->
        <div class="text-center pt-3">
            <p>Transfer the amounts to the business amount below. Please include invoice number on your check.
            <p class="bank-details">
                <span class="mr-4">BANK: <strong>FTSBUS33</strong></span>
                <span>IBAN: <strong>G882-1111-2222-3333</strong></span>
            </p>
        </div>
        <div class="text-center pt-3">
            <p class="mb-0"><strong>ADTEXO d.o.o. Sarajevo</strong>Tvornička 3, 71210 Ilidža, BIH | JIB: 4202476730003 | PIB: 202476730003</p>
        </div>
        @else
        <div class="document-footer pt-3">
            <div class="logo">
                <img src="{{ asset('assets/img/adtexo_logo_20201231.png').assetVersion() }}" alt="enabavka.ba" />
            </div>
            <div class="client-info">
                @if($document->rStock->country_id == 'bih')
                <p><strong class="black">ADTEXO d.o.o.</strong></p>
                <p><strong>ID</strong>: 4202476730003</p>
                <p><strong>PDV</strong>: 202476730003</p>
                @elseif($document->rStock->country_id == 'srb')
                <p><strong class="black">ADTEXO d.o.o. Priboj</strong></p>
                <p><strong>PIB</strong>: 112090920</p>
                <p><strong>MIB</strong>: 21605425</p>
                @endif
            </div>
            <div class="client-info">
                @if($document->rStock->country_id == 'bih')
                <p><strong>Adresa</strong>: Marka Marulića 2, 71000 Sarajevo</p>
                <p><strong>Br. računa</strong>: 1941410040000160</p>
                <p><strong>Tel.</strong>: +387 33 821 881</p>
                @elseif($document->rStock->country_id == 'srb')
                <p><strong>Adresa</strong>: Save Kovačevića 73, Priboj 31330</p>
                <p><strong>Br. računa</strong>: 160-6000000765042-40</p>
                <p><strong>Tel.</strong>: +381 11 422 9101</p>
                @endif
            </div>
        </div>
        @endif
        <!-- end: document footer -->
    </div>
</section>
<!-- end: document -->
</div>
<!-- end: content body -->
@endsection

@section('script-vendor')
<script src="{{ asset('assets/vendor/html2pdf.bundle.min.js').assetVersion() }}" type="text/javascript"></script>
@endsection

@section('script_inline')
@parent
<script>
    $(document).ready(function() {
        window.print();
        window.onafterprint = (event) => {
            window.close();
        };
    });
</script>
@endsection
