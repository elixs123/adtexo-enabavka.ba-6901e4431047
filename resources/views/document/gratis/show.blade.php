@extends('layouts.app', [
    'scoped_header' => false,
    'scoped_footer' => false,
])

@section('head_title', $title = trans('document.title'))

@section('content')
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
                            <li class="breadcrumb-item active">{{ 'Revers #'.$document->id.' '.$document->rClient->full_name }}</li>
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
                <div class="col-12">
                    <span class="btn" title="{{ trans('skeleton.data.status') }}" data-tooltip style="background-color: {{ $document->rStatus->background_color }};color: {{ $document->rStatus->color }}">{{ $document->rStatus->name }}</span>
                    <a href="{{ route('document.gratis.product', ['id' => $document->id, 'export' => 'pdf']) }}" class="btn btn-primary" data-export-pdf>{{ trans('skeleton.actions.export2pdf') }}</a>
                    <a href="{{ route('document.show', ['id' => $document->id]) }}" class="btn btn-warning">{{ $document->rType->name }}</a>
                </div>
            </div>
        </section>
        <!-- end: document functionality -->
        <!-- start: document -->
        <section id="document-print" class="card invoice-page">
            <div class="card-body">
                @include('document.show.company_header', ['document_name' => 'Revers', 'show_logo' => true])
                <!-- start: client details -->
                <div class="row pt-4">
                    <div class="col-sm-4 col-12 text-left">
                        <h5>Broj #<strong class="text-dark">{{ $document->id }}/{{ now()->format('Y') }}-R</strong></h5>
                        <div class="recipient-info pb-2">
                            <p>Datum dokumenta: <strong class="text-dark">{{ $document->date_of_order->format('d.m.Y') }}</strong></p>
                            <p>Kreiran: <strong class="text-dark">{{ $document->created_at->format('d.m.Y') }}</strong></p>
                            @if(!is_null($document->date_of_delivery))
                            <p>Isporuka: <strong class="text-dark">{{ $document->date_of_delivery->format('d.m.Y') }}</strong></p>
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
                        <div class="table-responsive col-12">
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
                                <tbody>@php $qty = $val = $net = $net_tax = 0; @endphp
                                    @foreach($gratis_products as $gratis_product)
                                    <tr class="text-right">@php $item = $gratis_product; @endphp
                                        <td class="text-left">
                                            <small>{{ $gratis_product->code }}</small>
                                            <br><strong>{{ $gratis_product->name }}</strong>
                                            <br><small>{{ $gratis_product->barcode }}</small>
                                        </td>
                                        <td>{{ $item->unit_id }}</td>@php $qty += $item->qty; @endphp
                                        <td>{{ $item->qty }}</td>@php $price = $document->useMpcPrice() ? $item->mpc : $item->vpc; @endphp
                                        <td>{{ format_price($price) }}</td>@php $disc = 0; @endphp
                                        <td>{{ format_price($disc) }}</td>@php $net_value = $document->useMpcPrice() ? getPriceWithoutVat($price - $disc, $document->tax_rate) : $price - $disc; $net += $net_value; @endphp
                                        <td>{{ format_price($net_value) }}</td>@php $vat_value = getVatFromPrice($net_value, $document->tax_rate)  @endphp
                                        <td>{{ format_price($vat_value) }}</td>@php $net_tax_value = $net_value + $vat_value; @endphp
                                        <td>{{ format_price($net_tax_value) }}</td>@php $net_tax_value = $item->qty * $net_tax_value @endphp
                                        <td>{{ format_price($net_tax_value) }}</td>@php $net_tax += $net_tax_value; @endphp
                                    </tr>
                                    @endforeach
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- end: document items -->
                @include('document.show.company_footer')
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
                    filename:     'Revers #{{ $document->id }}-{{ now()->format('Y') }}-R.pdf',
                    image:        { type: 'jpeg', quality: 0.98 },
                    html2canvas:  { scale: 3, windowWidth: 1200 },
                    jsPDF:        { unit: 'mm', format: 'a3', orientation: 'portrait' }
                };
                html2pdf().set(opt).from(el).save().run(function () {
                    loader_off();
                });
            });
        });
    </script>
@endsection
