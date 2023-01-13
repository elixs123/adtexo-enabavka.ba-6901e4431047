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
                @include('document.show.dropdown')
            </div>
        </div>
    </div>
    <!-- end: content header -->
    <!-- start: content body -->
    <div class="content-body">
        <!-- start: document functionality -->
        <section class="invoice-print mb-1">
            <div class="row">
                <div class="col-8">
                    @if($document->isOrder() && $document->is_payed)
                    <span class="btn btn-success" title="{{ trans('skeleton.data.status') }}" data-tooltip>Plaćeno</span>
                    @else
                    <span class="btn" title="{{ trans('skeleton.data.status') }}" data-tooltip style="background-color: {{ $document->rStatus->background_color }};color: {{ $document->rStatus->color }}">{{ $document->rStatus->name }}</span>
                    @endif
                    @if(userIsAdmin() || userIsEditor() || userIsWarehouse())
                    <a href="{{ route('document.show', ['id' => $document->id, 'export' => 'xls']) }}" class="btn btn-primary">{{ trans('skeleton.actions.export2xls') }}</a>
                    @if(false && $document->isAction() && $document->rAction->isGratis())
                    <a href="{{ route('document.show', ['id' => $document->id, 'export' => 'xls', 'export_type' => 'gratis']) }}" class="btn btn-primary">Gratis (.xls)</a>
                    @endif
                    @endif
                    @if((userIsAdmin() || userIsSalesman() || userIsWarehouse()) && !in_array($document->type, ['return']))
                    <a href="{{ route('document.show', ['id' => $document->id, 'export' => 'pdf']) }}" class="btn btn-primary" data-export-pdf>{{ trans('skeleton.actions.export2pdf') }}</a>
                    @endif
                    @if($document->isAction() && $document->rAction->isGratis())
                    <a href="{{ route('document.gratis.product', ['id' => $document->id]) }}" class="btn btn-warning">Revers</a>
                    @endif
                    @if($document->isOrder() && !in_array($document->status, ['reversed', 'canceled', 'delivered']))
                    <a href="{{ route('document.track.show', ['id' => $document->id]) }}" class="btn btn-dark" data-toggle="modal" data-target="#form-modal1">Track</a>
                    @endif
                </div>
                @if(can('edit-document') && in_array($document->status, ['in_process', 'in_warehouse', 'warehouse_preparing', 'for_invoicing']) && (userIsAdmin() || userIsEditor() || userIsWarehouse()))
                {!! Form::open(['url' => route('document.status.change'), 'method' => 'post', 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-document-'.$document->status).' col-4 text-right', 'data-status' => $document->status]) !!}
                    @if(($document->status == 'in_process') && userIsEditor())
                    <button type="button" class="btn btn-danger" data-document-status data-status="canceled">{{ trans('document.actions.status_to.canceled') }}</button>
                        @if($document->type_id == 'order')
                    <button type="button" class="btn btn-success" data-document-status data-status="in_warehouse">{{ trans('document.actions.status_to.in_warehouse') }}</button>
                            @else
                    <button type="button" class="btn btn-success" data-document-status data-status="completed">{{ trans('document.actions.status_to.completed') }}</button>
                        @endif
                    @elseif(($document->status == 'in_warehouse'))
                    <button type="button" class="btn btn-success" data-document-status data-status="warehouse_preparing">{{ trans('document.actions.status_to.warehouse_preparing') }}</button>
                    <button type="button" class="btn btn-danger" data-document-status data-status="canceled">{{ trans('document.actions.status_to.canceled') }}</button>
                    @elseif(($document->status == 'warehouse_preparing') && userIsWarehouse() && !is_null($document->package_number))
                    <button type="button" class="btn btn-success" data-document-status data-status="for_invoicing">{{ trans('document.actions.status_to.for_invoicing') }}</button>
                    @elseif(($document->status == 'for_invoicing') && (userIsEditor() || userIsWarehouse()))
                    <button type="button" class="btn btn-success" data-document-status data-status="invoiced">{{ trans('document.actions.status_to.invoiced') }}</button>
                    @endif
                    <input type="hidden" name="s" value="" required>
                    <input type="hidden" name="d[]" value="{{ $document->id }}" required>
                    <input type="hidden" name="t" value="{{ $document->type_id }}" required>
                {!! Form::close() !!}
                @endif
            </div>
        </section>
        <!-- end: document functionality -->
        <!-- start: document -->
        <section id="document-print" class="card invoice-page">
            <div class="card-body">
                @include('document.show.company_header', ['document_name' => $document->rType->name, 'show_logo' => true])
                <!-- start: client details -->
                <div class="row pt-4">
                    <div class="col-sm-4 col-12 text-left">
                        <h5>Broj #<strong class="text-dark">{{ $document->id }}/{{ $document->created_at->format('Y') }}</strong></h5>
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
                            <p>Skladište: <strong class="text-dark">{{ $document->date_of_warehouse->format('d.m.Y') }}</strong></p>
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
                            <p>Broj fiskalnog računa: <strong class="text-dark">{{ $document->fiscal_receipt_no }}</strong></p>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4 col-12 text-left">
                        @include('document.show.client')
                    </div>
                    <div class="col-sm-4 col-12">
                        @include('document.show.delivery')
                    </div>
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
                                        <th>{{ trans('document.data.quantity') }}</th>
                                        <th>{{ trans('document.data.unit_id') }}</th>
                                        @if(!userIsWarehouse())
                                        <th>{{ trans('document.data.show_price') }}</th>
                                        <th>R1 %</th>
                                        <th>R2 %</th>
                                        <th>R3 %</th>
                                        <th>{{ trans('document.data.show_net') }}</th>
                                        @if($document->isAction() && $document->rAction->isGratis())
                                        <th>{{ trans('document.data.show_gratis', ['percent' => $document->useMpcPrice() ? $document->rAction->total_discount : $document->rAction->subtotal_discount]) }}</th>
                                        @endif
                                        <th>{{ trans('document.data.vat', ['vat' => $document->tax_rate]) }}</th>
                                        <th>{{ trans('document.data.show_net_tax') }}</th>
                                        <th>{{ trans('document.data.show_total') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody data-document-products>
                                    @foreach($products as $item)
                                    <tr class="text-right">
                                        <td class="text-left">
                                            <small>{{ $item->code }}</small>
                                            <br><strong>{{ $item->name }}</strong>
                                            @if(isset($changes[$item->product_id]))
                                            <a href="{{ route('document.changes.index', ['id' => $document->id, 'product_id' => $item->product_id]) }}" data-toggle="modal" data-target="#form-modal1"><span class="feather icon-alert-triangle text-danger" title="{{ trans('document.data.changes') }}" data-tooltip></span></a>
                                            @endif
                                            <br><small>{{ $item->barcode }}</small>
                                            @if(isset($item->contract_id))
                                            <small class="badge badge-info text-uppercase">Ugovoreni lager</small>
                                            @endif
                                            @if(!isset($item->id))
                                            <small class="badge badge-info text-uppercase">Promo</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if(($document->type_id == 'order') && in_array($document->status, ['in_process', 'in_warehouse', 'warehouse_preparing']) && (userIsEditor() || userIsWarehouse()) && isset($item->id))
                                            <input type="number" class="form-control text-center" name="product[{{ $item->id }}]" value="{{ $item->qty }}" data-plugin-mask data-plugin-options='{"mask": "######0", "placeholder": "{{ $item->qty }}", "selectOnFocus": true}'>
                                                @else
                                            {{ $item->qty }}
                                            @endif
                                        </td>
                                        <td>{{ $item->unit_id }}</td>
                                        @if(!userIsWarehouse())
                                        <td>{{ format_price($item->fiscal_net_price) }}</td>
                                        <td>{{ format_price($item->discount1) }}</td>
                                        <td>{{ format_price($item->discount2) }}</td>
                                        <td>{{ format_price($item->discount3) }}</td>
                                        <td>{{ format_price($item->fiscal_net_discounted_price) }}</td>
                                        @if($document->isAction() && $document->rAction->isGratis())
                                            @php
                                            $_price = $document->useMpcPrice() ? $item->mpc : $item->vpc;
                                            $_disc = $_price - calculateDiscount($_price, $document->discount1, $document->discount2, ($document->useMpcPrice() ? $document->rAction->total_discount : $document->rAction->subtotal_discount));
                                            $_net_value = $document->useMpcPrice() ? getPriceWithoutVat($_price - $_disc, $document->tax_rate) : $_price - $_disc;
                                            @endphp
                                        <td>{{ format_price($_net_value) }}</td>
                                        @endif
                                        <td>{{ format_price($item->fiscal_discounted_price - $item->fiscal_net_discounted_price) }}</td>
                                        <td>{{ format_price($item->fiscal_discounted_price) }}</td>
                                        <td><strong>{{ format_price(($document->useMpcPrice() ? $item->total_discounted : getPriceWithVat($item->subtotal_discounted, $document->tax_rate)), 2) }}</strong></td>
                                        @endif
                                    </tr>
                                    @endforeach
                                    @if($gratis_products->count())
                                        @foreach($gratis_products as $gratis_product)
                                    <tr class="text-right hidden-from-export-pdf" data-html2canvas-ignore>@php $item = $gratis_product; @endphp
                                        <td class="text-left">
                                            <small>{{ $gratis_product->code }} <strong class="text-uppercase text-primary">Gratis</strong></small>
                                            <br><strong>{{ $gratis_product->name }}</strong>
                                            <br><small>{{ $gratis_product->barcode }}</small>
                                            @if(in_array($document->status, ['for_invoicing', 'invoiced']))
                                            <strong class="text-uppercase"><a href="{{ route('document.gratis.product', ['id' => $document->id]) }}">Revers</a></strong>
                                            @endif
                                        </td>
                                        <td>{{ $gratis_product->unit_id }}</td>
                                        <td>{{ $gratis_product->qty}}</td>
                                        @if(!userIsWarehouse())
                                        <td>{{ format_price(0) }}</td>
                                        <td>{{ format_price(0) }}</td>
                                        <td>{{ format_price(0) }}</td>
                                        <td>{{ format_price(0) }}</td>
                                        <td>{{ format_price(0) }}</td>
                                        <td>{{ format_price(0) }}</td>
                                        <td>{{ format_price(0) }}</td>
                                        <td>{{ format_price(0) }}</td>
                                        <td>{{ format_price(0) }}</td>
                                        @endif
                                    </tr>
                                        @endforeach
                                    @endif
                                    <tr class="table-active text-right text-bold-600">
                                        <td>Ukupno</td>
                                        <td>{{ $products->sum('qty') }}</td>
                                        <td>&nbsp;</td>
                                        @if(!userIsWarehouse())
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>{{ format_price($products->sum('fiscal_net_discounted_price')) }}</td>
                                        @if($document->isAction() && $document->rAction->isGratis())
                                        <td>&nbsp;</td>
                                        @endif
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td><strong>{{ format_price(($document->useMpcPrice() ? $document->total_discounted : getPriceWithVat(round($document->subtotal_discounted, 2), $document->tax_rate)), 2) }}</strong></td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                            @if(($document->type_id == 'order') && in_array($document->status, ['in_process', 'in_warehouse', 'warehouse_preparing']) && (userIsEditor() || userIsWarehouse()))
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
                                @if(!$document->isAction() && (userIsEditor() || userIsWarehouse()))
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
                                    <tbody>@php $document_total = $document->useMpcPrice() ? $document->total : $document->subtotal; $document_discounted = $document->useMpcPrice() ? $document->total_discounted : $document->subtotal_discounted; @endphp
                                        <tr>
                                            <th class="text-uppercase">Ukupno</th>
                                            <th class="text-right">{{ format_price($document_total) }} {{ $document->currency }}</th>
                                        </tr>
                                        <tr>
                                            <th class="text-uppercase">Rabat</th>
                                            <th class="text-right">{{ format_price($document_total - $document_discounted) }} {{ $document->currency }}</th>
                                        </tr>
                                        @if($document_total > 0 && $document_discounted > 0)
                                        <tr>
                                            <th class="text-uppercase">Rabat %</th>
                                            <th class="text-right">{{ format_price((1 - $document_discounted / $document_total) * 100) }} %</th>
                                        </tr>
                                      @endif
                                        <tr>
                                            <th class="text-uppercase">Ukupno sa rabatom</th>
                                            <th class="text-right">{{ format_price($document_discounted) }} {{ $document->currency }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>@php $price = $document->useMpcPrice() ? getPriceWithoutVat($document->total_discounted, $document->tax_rate) : $document->subtotal_discounted; @endphp
                                            <th class="text-uppercase">{{ trans('document.data.total_no_vat') }}</th>
                                            <td class="text-right"><strong>{{ format_price($price, 2) }} {{ $document->currency }}</strong></td>
                                        </tr>
                                        <tr>@php $vat = getVatFromPrice($price, $document->tax_rate); @endphp
                                            <th class="text-uppercase">{{ trans('document.data.vat', ['vat' => $document->tax_rate]) }}</th>
                                            <td class="text-right">{{ format_price($vat, 2) }} {{ $document->currency }}</td>
                                        </tr>
                                        <tr>@php $price_with_vat = $document->useMpcPrice() ? $document->total_discounted : round($price, 2) + round($vat, 2); @endphp
                                            <th class="text-uppercase">{{ trans('document.data.total_with_vat') }}</th>
                                            <td class="text-right">{{ format_price($price_with_vat, 2) }} {{ $document->currency }}</td>
                                        </tr>
                                        <tr> @php $delivery_cost = clientTypeDeliveryCost($document->delivery_cost, $document->rClient->type_id, $document->tax_rate); @endphp
                                            <th class="text-uppercase">{{ trans('document.data.delivery') }}</th>
                                            <td class="text-right">{{ format_price($delivery_cost, 2) }} {{ $document->currency }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-uppercase">Za plaćanje</th>
                                            <td class="text-right"><strong>{{ format_price($price_with_vat + $delivery_cost, 2) }} {{ $document->currency }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end: document total -->
                <!-- start: document additional info -->
                <div>
                    <p>Povrat robe mogu ostvariti kupci sa kojima imamo potpisan ugovor o poslovnoj saradnji. </p>
                    <p>Prodajni predstavnici AdTexo kompanije mogu preuzimati samo artikle koji su predmet fizičke zamjene. </p>
                    <p>Prodajni predstavnici AdTexo kompanije nisu ovlašteni preuzimati povrat robe od kupaca.</p>
                </div>
                <!-- end: document additional info-->
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
                @include('document.show.company_footer')
            </div>
        </section>
        <!-- end: document -->
    </div>
    <!-- end: content body -->
    @if(!userIsWarehouse() && ((config('app.env') == 'local') || (config('app.url') == 'https://dev.enabavka.ba') || (request('fiscal_debug') == '1')))
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <p><strong>Document / Invoice</strong></p>
                    <table class="table table-bordered table-condesed">
                        <tr>
                            <td>Document</td>
                            <td>NetPrice</td>
                            <td>GrossPrice</td>
                            <td>DiscountedPrice</td>
                            <td>Discount</td>
                            <td>VAT</td>
                        </tr>
                        <tr>
                            <td>{{ $document->id }}/{{ $document->created_at->format('Y') }}</td>
                            <td>{{ $document->fiscal_net_price }}</td>
                            <td>{{ $document->fiscal_gross_price }}</td>
                            <td>{{ $document->fiscal_discounted_price }}</td>
                            <td>{{ $document->fiscal_discount_percent }} %</td>
                            <td>{{ $document->fiscal_vat }}</td>
                        </tr>
                    </table>
                    <p><strong>Products / Items</strong></p>
                    <table class="table table-bordered table-condesed">
                        <tr>
                            <td>Product</td>
                            <td>NetPrice</td>
                            <td>NetDiscountedPrice</td>
                            <td>GrossPrice</td>
                            <td>DiscountedPrice</td>
                            <td>Discount</td>
                            <td>Qty</td>
                            <td>Total</td>
                        </tr>@php $total = 0; @endphp
                        @foreach($products as $item)
                        <tr>
                            <td>{{ $item->barcode }}</td>
                            <td>{{ $item->fiscal_net_price }}</td>
                            <td>{{ $item->fiscal_net_discounted_price }}</td>
                            <td>{{ $item->fiscal_gross_price }}</td>
                            <td>{{ $item->fiscal_discounted_price }}</td>
                            <td>{{ $item->fiscal_discount_percent }} %</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $price = round($item->qty * $item->fiscal_discounted_price, 2) }}</td>>@php $total += $price; @endphp
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="7">&nbsp;</td>
                            <td>{{ $total }}</td>
                        </tr>
                    </table>
                    <p><strong>Delivery</strong></p>
                    <table class="table table-bordered table-condesed">
                        <tr>
                            <td>NetPrice</td>
                            <td>GrossPrice</td>
                            <td>FiscalDeliveryPrice</td>
                        </tr>
                        <tr>
                            <td>{{ $document->fiscal_delivery_net_price }}</td>
                            <td>{{ $document->fiscal_delivery_gross_price }}</td>
                            <td>{{ $document->fiscal_delivery_price }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    @endif
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
            @if(($document->type_id == 'order') && in_array($document->status, ['in_process', 'in_warehouse', 'warehouse_preparing']) && (userIsEditor() || userIsWarehouse()))
            // Document products change
            $('button[data-document-products-change]').click(function (e) {
                // Loader: On
                loader_on();
            });
            // Mask
            maskPlugin($('[data-document-products]'));
            @endif
            @if(in_array($document->status, ['shipped', 'express_post_in_process']))
            HttpRequest.get("{{ route('expresspost.status', ['id' => $document->id, 'status' => $document->status]) }}", {}, function(response) {
                if (response.reload) {
                    loader_on();
                    
                    documentReload();
                }
            });
            @endif
        });
    </script>
@endsection
