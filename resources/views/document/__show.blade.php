@extends('layouts.app', [
    'scoped_header' => (ScopedDocument::id() == $document->id),
    'scoped_footer' => (ScopedDocument::id() == $document->id),
])

@section('head_title', $title = trans('document.title'))

@section('content')
    @include('partials.alert_box')
    <!-- start: content header -->
    <div class="content-header row">
        <div class="content-header-left col-9 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">{{ trans('skeleton.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('document.index') }}">{{ $title }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $document->full_name }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right text-right col-3">
            <div class="form-group breadcrum-right">
                <div class="dropdown">
                    <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings"></i></button>
                    <div class="dropdown-menu dropdown-menu-right p-0">
                        @if(ScopedDocument::exist())
                            @if(ScopedDocument::id() == $document->id)
                        <a class="dropdown-item" href="{{ route('document.close') }}" data-scoped-document-close>{{ trans('document.actions.close') }}</a>
                                @if(($document->status == 'draft'))
                        <a class="dropdown-item" href="{{ ScopedDocument::isOrder() ? route('cart.index') : route('document.draft.complete') }}" data-scoped-document-complete>{{ trans('document.actions.complete.'.$document->type_id) }}</a>
                                @endif
                            @endif
                        @else
                            @if(in_array($document->status, ['draft']))
                        <a class="dropdown-item" href="{{ route('document.open', [$document->id]) }}" data-tooltip data-scoped-document-open>{{ trans('document.actions.open') }}</a>
                            @endif
                            @if(can('create-document'))
                        <a class="dropdown-item" href="{{ route('document.copy', [$document->id]) }}" data-document-copy data-text="{{ trans('skeleton.copy_msg') }}">{{ trans('document.actions.copy') }}</a>
                                @if(userIsClient())
                        <a class="dropdown-item" href="{{ route('document.create', ['type_id' => 'order']) }}" data-toggle="modal" data-target="#form-modal1">{{ trans('document.actions.new.order') }}</a>
                                @else
                                    @foreach(trans('document.actions.create') as $key => $value)
                        <a class="dropdown-item" href="{{ route('document.create', ['type_id' => $key, 'client_id' => $document->client_id]) }}" data-toggle="modal" data-target="#form-modal1">{{ $value }}</a>
                                    @endforeach
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end: content header -->
    <!-- start: content body -->
    <div class="content-body">
        <!-- start: document functionality -->
        <section class="invoice-print mb-1">
            <div class="row">
                <div class="col-6">
                    <span class="btn" title="{{ trans('skeleton.data.status') }}" data-tooltip style="background-color: {{ $document->rStatus->background_color }};color: {{ $document->rStatus->color }}">{{ $document->rStatus->name }}</span>
                    @if(userIsAdmin() || userIsEditor() || userIsWarehouse())
                    <a href="{{ route('document.show', ['id' => $document->id, 'export' => 'xls']) }}" class="btn btn-primary">{{ trans('skeleton.actions.export2xls') }}</a>
                    @endif
                    @if(userIsSalesman() && !in_array($document->type, ['return']))
                    <a href="{{ route('document.show', ['id' => $document->id, 'export' => 'pdf']) }}" class="btn btn-primary" data-export-pdf>{{ trans('skeleton.actions.export2pdf') }}</a>
                    @endif
                </div>
                @if(can('edit-document') && in_array($document->status, ['in_process', 'in_warehouse', 'for_invoicing']) && (userIsAdmin() || userIsEditor() || userIsWarehouse()))
                {!! Form::open(['url' => route('document.status.change'), 'method' => 'post', 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-document-'.$document->status).' col-6 text-right', 'data-status' => $document->status]) !!}
                    @if(($document->status == 'in_process') && userIsEditor())
                    <button type="button" class="btn btn-danger" data-document-status data-status="canceled">{{ trans('document.actions.status_to.canceled') }}</button>
                        @if($document->type_id == 'order')
                    <button type="button" class="btn btn-success" data-document-status data-status="in_warehouse">{{ trans('document.actions.status_to.in_warehouse') }}</button>
                            @else
                    <button type="button" class="btn btn-success" data-document-status data-status="completed">{{ trans('document.actions.status_to.completed') }}</button>
                        @endif
                    @elseif(($document->status == 'in_warehouse') && userIsWarehouse() && !is_null($document->package_number))
                    <button type="button" class="btn btn-success" data-document-status data-status="for_invoicing">{{ trans('document.actions.status_to.for_invoicing') }}</button>
                    @elseif(($document->status == 'for_invoicing') && userIsEditor())
                    <button type="button" class="btn btn-success" data-document-status data-status="invoiced">{{ trans('document.actions.status_to.invoiced') }}</button>
                    @endif
                    <input type="hidden" name="s" value="" required>
                    <input type="hidden" name="d[]" value="{{ $document->id }}" required>
                {!! Form::close() !!}
                @endif
            </div>
        </section>
        <!-- end: document functionality -->
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
                        <div class="recipient-info">@php $is_location = array_get($document->buyer_data, 'is_location', false); $code = $is_location ? array_get($document->buyer_data, 'location_code', '-') : array_get($document->buyer_data, 'code', '-');  @endphp
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
                                        @if(!userIsWarehouse())
                                        <th>{{ trans('document.data.vpc') }}</th>
                                            @if($has_contract_discount = ($products->sum('contract_discount') > 0))
                                        <th>{{ trans('document.data.discount_contract') }}</th>
                                            @endif
                                        <th>{{ trans('document.data.discount1') }} ({{ $discount1 = $document->discount1 }}%)</th>
                                            @if($discount2 = $document->discount2)
                                        <th>{{ trans('document.data.discount2') }} ({{ $discount2 }}%)</th>
                                            @endif
                                        <th>{{ trans('document.data.vpc_discounted') }}</th>
                                        <th>{{ trans('document.data.vat', ['vat' => $document->tax_rate]) }}</th>
                                        <!--<th>{{ trans('document.data.mpc') }}</th>-->
                                        <th>{{ trans('document.data.total') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody data-document-products>@php $qty = $vpc = $disc = $vat = $neto = $mpc = $total = 0; @endphp
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
                                            @if(($document->type_id == 'order') && in_array($document->status, ['in_process', 'in_warehouse']) && (userIsEditor() || userIsWarehouse()))
                                            <input type="number" class="form-control text-center" name="product[{{ $item->id }}]" value="{{ $item->qty }}" data-plugin-mask data-plugin-options='{"mask": "######0", "placeholder": "{{ $item->qty }}", "selectOnFocus": true}'>
                                                @else
                                            {{ $item->qty }}
                                            @endif
                                        </td>@php $qty += $item->qty; @endphp
                                        @if(!userIsWarehouse())
                                        <td>{{ format_price($price = $item->price) }}</td>
                                            @if($has_contract_discount)
                                        <td>{{ format_price(getDiscountedValue($price, $item->contract_discount)) }}</td>@php $price = calculateDiscount($price, $item->contract_discount);  @endphp
                                            @endif
                                        @php $vpc += $item->qty * $price; $item_dics = getDiscountedValue($price, $discount1); @endphp
                                        <td>{{ format_price($item_dics) }}</td>@php $item_discounted = $price - $item_dics; @endphp
                                            @if($discount2) @php $item_dics = getDiscountedValue($price - $item_dics, $discount2); @endphp
                                        <td>{{ format_price($item_dics) }}</td>@php $item_discounted -= $item_dics; @endphp
                                            @endif
                                        <td>{{ format_price($item_discounted) }}</td>@php $neto += $item->qty * $item_discounted; @endphp
                                        <td>{{ format_price($item_vat = getDiscountedValue($document->tax_rate, $item_discounted)) }}</td>@php $vat += $item->qty * $item_vat; @endphp
                                        <!--<td>{{ format_price($item_mpc = getDiscountedValue(100 + $document->tax_rate, $item_discounted)) }}</td>@php $mpc += $item_mpc; @endphp-->
                                        <td><strong>{{ format_price($item->qty * $item_mpc) }}</strong></td>@php $total += $item->qty * $item_mpc; @endphp
                                        @endif
                                    </tr>
                                    @endforeach
                                    @if($products->count())
                                    <tr class="table-active text-right text-bold-600">
                                        <td>&nbsp;</td>
                                        <td>Ukupno</td>
                                        <td>{{ $qty }}</td>
                                        @if(!userIsWarehouse())
                                        <td>{{ format_price($vpc) }}</td>
                                        <td>&nbsp;</td>
                                            @if($has_contract_discount)
                                        <td>&nbsp;</td>
                                            @endif
                                            @if($discount2)
                                        <td>&nbsp;</td>
                                            @endif
                                        <td>{{ format_price($neto) }}</td>
                                        <td>{{ format_price($vat) }}</td>
                                        <!--<td>&nbsp;</td>-->
                                        <td><strong>{{ format_price($total) }}</strong></td>
                                        @endif
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
                                @if(userIsEditor())
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
                @if(!userIsWarehouse())
                <!-- start: document total -->
                <div class="invoice-total-table">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-2">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        @if(($document->discount1 > 0) || ($document->discount2 > 0))
                                        <tr>
                                            <th class="text-uppercase">{{ trans('document.data.subtotal_no_vat') }}</th>
                                            <td class="text-right">{{ format_price($vpc) }} {{ $document->currency }}</td>
                                        </tr>
                                        <tr>@php $discounted = getDiscountedValue($document->discount1, $vpc); $vpc -= $discounted; @endphp
                                            <th class="text-uppercase">{{ trans('document.data.discount_num', ['num' => 1, 'discount' => $document->discount1]) }}</th>
                                            <td class="text-right @if($discounted){{ 'text-success' }}@endif">@if($discounted){{ '- ' }}@endif{{ format_price($discounted) }} {{ $document->currency }}</td>
                                        </tr>
                                        @if($document->discount2 > 0)
                                        <tr>@php $discounted = getDiscountedValue($document->discount2, $vpc); $vpc -= $discounted; @endphp
                                            <th class="text-uppercase">{{ trans('document.data.discount_num', ['num' => 2, 'discount' => $document->discount_value1]) }}</th>
                                            <td class="text-right @if($discounted){{ 'text-success' }}@endif">@if($discounted){{ '- ' }}@endif{{ format_price($discounted) }} {{ $document->currency }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th class="text-uppercase">{{ trans('document.data.subtotal_with_discount') }}</th>
                                            <td class="text-right"><strong>{{ format_price($vpc) }} {{ $document->currency }}</strong></td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th class="text-uppercase">{{ trans('document.data.total_no_vat') }}</th>
                                            <td class="text-right"><strong>{{ format_price($vpc) }} {{ $document->currency }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th class="text-uppercase">{{ trans('document.data.vat', ['vat' => $document->tax_rate]) }}</th>
                                            <td class="text-right">{{ format_price(getDiscountedValue($document->tax_rate, $vpc)) }} {{ $document->currency }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-uppercase">{{ trans('document.data.total_with_vat') }}</th>
                                            <td class="text-right">{{ format_price($total_with_tax = getDiscountedValue(100 + $document->tax_rate, $vpc)) }} {{ $document->currency }}</td>
                                        </tr>
                                        <tr> @php $delivery_cost = calcDeliveryCost($document->delivery_type, $document->rStock->country_id, $vpc, $document->delivery_cost); @endphp
                                            <th class="text-uppercase">{{ trans('document.data.delivery') }}</th>
                                            <td class="text-right">{{ format_price($delivery_cost) }} {{ $document->currency }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-uppercase">{{ trans('document.data.total') }}</th>
                                            <td class="text-right"><strong>{{ format_price($total_with_tax + $delivery_cost) }} {{ $document->currency }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end: document total -->
                @endif
                @if(!is_null($document->package_number))
                <!-- start: package number & weight -->
                <p>{{ trans('document.data.package_number') }}: <strong>{{ $document->package_number }}</strong></p>
                    @if(!is_null($document->weight))
                <p>{{ trans('document.data.weight') }}: <strong>{{ $document->weight }}</strong></p>
                    @endif
                <!-- end: package number & weight -->
                @endif
                @if(!userIsClient() && !is_null($document->note) && $document->note)
                <!-- start: document note -->
                <div class="invoice-total-table p-1 bg-light mb-3">
                    <h4>Napomena</h4>
                    <div class="bg-white p-1">{!! nl2br($document->note) !!}</div>
                </div>
                <!-- end: document note -->
                @endif
                @if(!userIsClient() && !userIsWarehouse() && !is_null($document->rParent) && (!$document->rParent->isCash()) && ($document->rParent->subtotal > 0))
                <!-- start: preorder summary -->@php $parent = $document->rParent; @endphp
                <div class="invoice-total-table p-1 bg-{{ $diff_color = $document->getDifferenceColor() }} bg-lighten-5">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <h4>{{ $parent->rType->name }} #{{ $parent->id }} <span class="feather {{ $document->getDifferenceIcon() }} bg-{{ $diff_color }} text-white"></span></h4>
                            <p>{{ trans('document.data.date_of_order') }}: <strong>{{ $parent->date_of_order->format('d.m.Y') }}</strong></p>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table mb-0 bg-white text-center">
                                    <thead>
                                        <tr>
                                            <th>Planirano</th>
                                            <th>Ostvareno</th>
                                            <th>Realizacija</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>{{ format_price($parent->total_discounted_value) }} {{ $document->currency }}</strong></td>
                                            <td><strong>{{ format_price($document->total_discounted_value) }} {{ $document->currency }}</strong></td>
                                            <td><strong class="text-{{ $diff_color }}">{{ format_price($document->getDifferenceFromParent() * 100) }}%</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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
                        <p><strong>PIB</strong>: 21605425</p>
                        <p><strong>MIB</strong>: 112090920</p>
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
        $(document).ready(function () {
            $('[data-export-pdf]').click(function (e) {
                e.preventDefault();
                loader_on();
                var el = document.getElementById('document-print');
                var opt = {
                    margin:       2,
                    filename:     '{{ $document->rType->name }} #{{ $document->id }}-{{ now()->format('Y') }}.pdf',
                    image:        { type: 'jpeg', quality: 0.98 },
                    html2canvas:  { scale: 3, windowWidth: 1200 },
                    jsPDF:        { unit: 'mm', format: 'a3', orientation: 'portrait' }
                };
                html2pdf().set(opt).from(el).save().run(function () {
                    loader_off();
                });
            });
            @can('edit-document')
            $('button[data-document-status]').click(function (e) {
                // Prevent default
                e.preventDefault();
                // Status
                $(this).parent().find('input[name="s"]:eq(0)').val($(this).data('status'));
                // Loader: On
                loader_on();
                // Request
                var $form = $(this).parent();
                HttpRequest.post($form.attr('action') + '?' + $form.serialize(), {}, function (response) {
                    // Check
                    if (response.redirect) {
                        // Loader: Off
                        loader_off();
                        // Redirect
                        document.location = response.redirect;
                    } else {
                        // Document: Reload
                        documentReload();
                    }
                });
            });
            @endcan
            @if(($document->type_id == 'order') && in_array($document->status, ['in_process', 'in_warehouse']) && (userIsEditor() || userIsWarehouse()))
            // Document products change
            $('button[data-document-products-change]').click(function (e) {
                // Loader: On
                loader_on();
            });
            // Mask
            maskPlugin($('[data-document-products]'));
            @endif
        });
    </script>
@endsection
