@extends('layouts.app', [
    'body_class' => 'ecommerce-application',
    'scoped_header' => ScopedDocument::exist(),
    'scoped_footer' => ScopedDocument::exist(),
])

@section('head_title', $title = $action->name)

@section('css-vendor')
    <link href="{{ asset('assets/app-assets/css/pages/app-ecommerce-shop.css').assetVersion() }}" rel="stylesheet" type="text/css">
@endsection

@section('css')
    @parent
    @include('shop._style')
    <style>
        @media (min-width: 768px){
            .ecommerce-application .grid-view {
                grid-template-columns: 1fr 1fr 1fr;
            }
        }
        @media (min-width: 992px){
            .ecommerce-application .grid-view {
                grid-template-columns: 1fr 1fr 1fr 1fr;
            }
        }
        @media (min-width: 1600px){
            .ecommerce-application .grid-view {
                grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
            }
        }
        @media (min-width: 1800px){
            .ecommerce-application .grid-view {
                grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Akcije</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('action.show', ['id' => $action->id]) }}">{{ $title }}</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-detached">
        <div class="content-body">
            <!-- start: header -->
            <section id="ecommerce-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="ecommerce-header-items">
                            <div class="result-toggler">
                                <div class="search-results">
                                    <span class="badge badge-lg badge-info">{{ $action->started_at->format('d.m.Y') }} - {{ $action->finished_at->format('d.m.Y') }}</span>
                                    <span class="badge badge-lg badge-dark">Dostupno: {{ $action->available_qty }}</span>
                                </div>
                            </div>
                            <div class="view-options d-print-none">
                                <div class="btn-group">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle mr-1" type="button" id="dropdownSort" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Opcije
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownSort">
                                            <a href="{{ route('action.show', ['id' => $action->id, 'export' => 'pdf']) }}" class="dropdown-item" data-export-pdf>Export (.pdf)</a>
                                            @if($action->presentation)
                                            <a href="{{ asset(config('file.action.path').'/'.$action->presentation) }}" class="dropdown-item" target="_blank">Prezentacija (.pdf)</a>
                                            @endif
                                            @if($action->technical_sheet)
                                            <a href="{{ asset(config('file.action.path').'/'.$action->technical_sheet) }}" class="dropdown-item" target="_blank">Tehnički list (.pdf)</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="view-btn-option">
                                    <button class="btn btn-white view-btn grid-view-btn active">
                                        <i class="feather icon-grid"></i>
                                    </button>
                                    <button class="btn btn-white list-view-btn view-btn">
                                        <i class="feather icon-list"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: header -->
            <!-- start: content -->
            <section id="ecommerce-products" class="grid-view">@php $total = 0; $discount = 0; @endphp
                @foreach($products_action as $product_id => $product_action)
                    @if(isset($products[$product_id])) @php $item = $products[$product_id]; $unit = is_null($item->rUnit) ? '' : $item->rUnit->name; @endphp
                <div class="card ecommerce-card">
                    <div class="card-content">
                        <div class="item-img text-center pt-0">
                            <a href="{{ url('shop/' . str_slug($item->name) . '/' . $item->id ) }}">
                                <img class="img-fluid" src="{{ $item->photo_small }}" alt="{{ $item->name }}">
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="item-wrapper">
                                <h6 class="item-price">
                                    {{ format_price($product_action->price_discounted, 2) }} {{ $currency }}
                                </h6>
                                <h6 class="item-price old">
                                    {{ format_price($product_action->price, 2) }} {{ $currency }}
                                </h6>@php $total += $product_action->qty * $product_action->price; $discount += $product_action->qty * ($product_action->price - $product_action->price_discounted) @endphp
                            </div>
                            <div class="item-name">
                                <a href="{{ url('shop/' . str_slug($item->name) . '/' . $item->id ) }}">{{ $item->name }}</a>
                            </div>
                            <span class="code">Šifra: <span>{{ $item->code }}</span></span>
                            @if($item->barcode != '')
                            <span class="code">Barcode: <span>{{ $item->barcode }}</span></span>
                            @endif
                            @if($item->packing != '')
                            <span class="code">Pakovanje: {{ $item->packing }}</span>
                            @endif
                            @if($item->transport_packaging != '')
                            <span class="code">Transportno pakovanje: {{ $item->transport_packaging }}</span>
                            @endif
                            @if($item->palette != '')
                            <span class="code">Paleta: {{ $item->palette }}</span>
                            @endif
                            <span class="stock-in">Količina: <span>{{ $product_action['qty'] }} {{ $unit }}</span></span>
                        </div>
                        <div class="item-options">
                            <div class="item-wrapper">
                                <div class="item-cost">
                                    <h6 class="item-price">
                                        {{ format_price($product_action->price_discounted, 2) }} {{ $currency }}
                                    </h6>
                                    <h6 class="item-price old">
                                        {{ format_price($product_action->price, 2) }} {{ $currency }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    @endif
                @endforeach
            </section>
            <!-- end: content -->
            <!-- start: total -->
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4 text-center">
                            Ukupno: <strong>{{ format_price($total) }} {{ $currency }}</strong>
                        </div>
                        <div class="col-12 col-md-4 text-center">
                            Rabat: <strong>{{ format_price($discount) }} {{ $currency }}</strong>
                        </div>
                        <div class="col-12 col-md-4 text-center">
                            Vrijednost akcije: <strong>{{ format_price($total - $discount) }} {{ $currency }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end: total -->
            
            @if(($action->isGratis()) && $products_gratis->count())
            <!-- start: gratis -->
            <p><strong class="text-uppercase text-primary">Gratis proizvodi</strong></p>
            <section class="list-view">
                @foreach($products_gratis as $product_id => $product_gratis)
                    @if(isset($products[$product_id])) @php $item = $products[$product_id]; $unit = is_null($item->rUnit) ? '' : $item->rUnit->name; @endphp
                <div class="card ecommerce-card">
                    <div class="card-content">
                        <div class="item-img text-center pt-0">
                            <a href="{{ url('shop/' . str_slug($item->name) . '/' . $item->id ) }}">
                                <img class="img-fluid" src="{{ $item->photo_small }}" alt="{{ $item->name }}">
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="item-wrapper">
                                <h6 class="item-price">
                                    {{ format_price($product_gratis->price_discounted, 2) }} {{ $currency }}
                                </h6>
                                @if(false)
                                <h6 class="item-price old">
                                    {{ format_price($product_gratis->price, 2) }} {{ $currency }}
                                </h6>
                                @endif
                            </div>
                            <div class="item-name">
                                <a href="{{ url('shop/' . str_slug($item->name) . '/' . $item->id ) }}">{{ $item->name }}</a>
                            </div>
                            <span class="code">Šifra: <span>{{ $item->code }}</span></span>
                            @if($item->barcode != '')
                            <span class="code">Barcode: <span>{{ $item->barcode }}</span></span>
                            @endif
                            @if($item->packing != '')
                            <span class="code">Pakovanje: {{ $item->packing }}</span>
                            @endif
                            @if($item->transport_packaging != '')
                            <span class="code">Transportno pakovanje: {{ $item->transport_packaging }}</span>
                            @endif
                            @if($item->palette != '')
                            <span class="code">Paleta: {{ $item->palette }}</span>
                            @endif
                            <span class="stock-in">Količina: <span>{{ $product_gratis->qty }} {{ $unit }}</span></span>
                        </div>
                        <div class="item-options">
                            <div class="item-wrapper">
                                <div class="item-cost">
                                    <h6 class="item-price">
                                        {{ format_price($product_gratis->price, 2) }} {{ $currency }}
                                    </h6>
                                    @if(false)
                                    <h6 class="item-price old">
                                        {{ format_price($product_gratis->price, 2) }} {{ $currency }}
                                    </h6>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    @endif
                @endforeach
            </section>
            <!-- end: gratis -->
            @endif
        <!-- start: options -->
            <section class="row">
                <div class="col-12 col-lg-6 col-xl-6"></div>
                <div class="col-12 col-lg-6 col-xl-6 text-right">
                    @can('create-document')
                        @if($action_qty_cond = ScopedDocument::exist() && (ScopedDocument::isOrder() || ScopedDocument::isCash()) && (ScopedDocument::isAction() || ScopedDocument::products()->count() == 0) && ($action->available_qty > 0) && (is_null(ScopedDocument::getDocument()->action_id) || (ScopedDocument::getDocument()->action_id == $action->id)))
                    {!! Form::open(['url' => route('action.cart', ['id' => $action->id]), 'method' => 'post', 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-actions-qty'), 'data-callback' => 'actionAddedToOrder']) !!}
                    <div class="row">
                        <div class="col-12 col-md-6 text-center">
                            {!! VuexyAdmin::selectTwo('quantity', $range, ScopedDocument::getDocument()->action_qty, ['data-plugin-options' => '{}', 'id' => 'form-control-quantity-actions'], '') !!}
                        </div>
                        <div class="col-12 col-md-6">
                            <button class="btn btn-primary btn-block" type="submit">{{ (ScopedDocument::getDocument()->action_id == $action->id) ? trans('skeleton.actions.change') : trans('skeleton.actions.add') }}</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                        @endif
                        @if(!ScopedDocument::exist() && ($action->available_qty > 0))
                    <a href="{{ route('document.create', ['type_id' => 'order', 'back' => route('action.show', ['id' => $action->id])]) }}" class="btn btn-primary" data-toggle="modal" data-target="#form-modal1">{{ trans('document.actions.create.order') }}</a>
                    <span> ili </span>
                    <a href="{{ route('document.create', ['type_id' => 'cash', 'back' => route('action.show', ['id' => $action->id])]) }}" class="btn btn-primary" data-toggle="modal" data-target="#form-modal1">{{ trans('document.actions.create.cash') }}</a>
                        @endif
                    @endcan
                </div>
            </section>
            <!-- end: options -->
        </div>
    </div>
@endsection

@section('script-vendor')
    <script src="{{ asset('assets/app-assets/js/scripts/pages/app-ecommerce-shop.js').assetVersion() }}" type="text/javascript"></script>
@endsection

@section('script')
    @if(isset($action_qty_cond) && $action_qty_cond)
    <script>
        $(document).ready(function () {
            App.validate('.{{ $form_class }}', {
                submitHandler: function(form) {
                    AjaxForm.init('.{{ $form_class }}');
                }
            });
        });
        
        function actionAddedToOrder(response) {
            Cart.update_cart_header(response);
            // documentRedirect(response);
        }
    </script>
    @endif
@endsection
