@extends('layouts.app')

@section('head_title', $title = $item->name)

@section('content')
    <?php $scopedProduct = ScopedDocument::hasProduct($item->id); $contractProduct = ScopedContract::getProduct($item->id); $unit = is_null($item->rUnit) ? '' : $item->rUnit->name; ?>

     <!-- BEGIN: Content-->
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="/shop">Shop</a>
                                    </li>
                                    <li class="breadcrumb-item active">Detalji proizvoda
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- app ecommerce details start -->
                <section class="app-ecommerce-details">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-5 mt-2">
                                <div class="col-12 col-md-5 mb-2 mb-md-0">
										<div data-fit="scaledown" data-nav="thumbs" data-width="100%" data-ratio="600/600" data-maxwidth="600"  class="fotorama">
											<img src="{{ asset('assets/img/noimage.jpeg') }}" class="img-fluid" alt="">
											@foreach($gallery as $photo_item)
											<img src="" alt="" />
											@endforeach
										</div>
                                </div>
                                <div class="col-12 col-md-6">
									<p class="text-muted">Ime branda</p>
                                    <h5>{{ $item->name }}</h5>
                                    <p class="text-muted">Šifra: {{$item->acIdent}}</p>
									<hr>
                                    <div class="ecommerce-details-price d-flex flex-wrap">
                                        <p class="text-primary font-medium-3 mr-1 mb-0">{{ format_price($item->anSalePrice, 2) }} {{ $currency }}</p>
                                        

										<span class="badge badge-primary badge-md"><small>{{ $item->loyalty_points }}</small> <i class="feather icon-award"></i></span>
                                    </div>
									<hr>
									
									@if($item->text != '')
                                    {!! $item->text !!}
									<hr>
									@endif
									
									@if($item->weight > 0 || $item->length > 0 || $item->width > 0 || $item->height > 0)
									<h6 class="text-primary text-uppercase">Dimenzije</h6>
									<ul>
										@if($item->weight > 0)
										<li>Težina: {{ $item->weight }} gr</li>
										@endif
										@if($item->length > 0)
										<li>Dužina: {{ $item->length }} cm</li>
										@endif
										@if($item->width > 0)
										<li>Širina: {{ $item->width }} cm</li>
										@endif
										@if($item->height > 0)
										<li>Visina: {{ $item->height }} cm</li>
										@endif
									</ul>
									<hr>
									@endif
									
									@if($item->packing != '' || $item->transport_packaging != '' ||$item->palette != '')
									<h6 class="text-primary text-uppercase">Pakovanja</h6>
									<ul>
										@if($item->packing != '')
										<li>Pakovanje: {{ $item->packing }}</li>
										@endif
										@if($item->transport_packaging != '')
										<li>Transportno pakovanje: {{ $item->transport_packaging }}</li>
										@endif
										@if($item->palette != '')
										<li>Paleta: {{ $item->palette }}</li>
										@endif
									</ul>
									<hr>
									@endif
    
                                    @if(!is_null($contractProduct))
                                    <p><span class="badge badge-info text-uppercase">Ugovoreno: {{ $contractProduct->qty }} {{ $unit }}</span> / <span class="badge badge-danger text-uppercase">Kupljeno: {{ $contractProduct->bought }} {{ $unit }}</span></p>
                                    @endif
                                    @if(!userIsClient())
                                    <p>Zaliha: <span  @if($item->qty > 0) class="text-success" @endif>{{ $item->qty }} {{ is_null($item->rUnit) ? '' : $item->rUnit->name }}</span></p>
                                    @endif
                                    
                                    <div class="d-flex flex-column flex-sm-row">
										@if(ScopedDocument::exist())
										<div class="input-group quantity-counter-wrapper">
											<input class="add-to-basket quantity-counter" type="number" autocomplete="off" data-max="{{ $item->qty }}" id="product_quantity_{{ $item->id }}" data-product-id="{{ $item->id }}" name="qty" placeholder="0" data-min="{{ $min_qty = ScopedDocument::getProductMinQty($item->id) }}" value="{{ isset($scopedProduct->qty) ? $scopedProduct->qty : '' }}">
										</div>
										@else
                                        <input class="choose-document" type="number" name="qty" placeholder="0" value="0" title="Izaberi dokument" data-tooltip data-toggle="modal" min="0" data-target="#form-modal1" data-href="{{ route('document.draft.index') }}" readonly>
										@endif
                                    </div>
                                    @if((isset($min_qty)) && ($min_qty > 0))
                                    <small><strong>Min. količina {{ $min_qty }}</strong></small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="item-features py-5">
                            <div class="row text-center pt-2">
                                <div class="col-12 col-md-4 mb-4 mb-md-0 ">
                                    <div class="w-75 mx-auto">
                                        <i class="feather icon-award text-primary font-large-2"></i>
                                        <h5 class="mt-2 font-weight-bold">100% Original</h5>
                                        <p>Svi naši proizvodi su testirani i dolaze sa garancijom.</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 mb-4 mb-md-0">
                                    <div class="w-75 mx-auto">
                                        <i class="feather icon-clock text-primary font-large-2"></i>
                                        <h5 class="mt-2 font-weight-bold">Brza isporuka</h5>
                                        <p>Isporuka na željenu adresu za 48h.</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 mb-4 mb-md-0">
                                    <div class="w-75 mx-auto">
                                        <i class="feather icon-shield text-primary font-large-2"></i>
                                        <h5 class="mt-2 font-weight-bold">Povrat robe</h5>
                                        <p>Moguće unutar 15 dana.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
						@if(count($related) > 0)
                        <div class="card-body">
                            <div class="mt-4 mb-2 text-center">
                                <h2>POVEZANI PROIZVODI</h2>
                                <p>Mogli bi vas zanimati i ovi proizvodi</p>
                            </div>
                            <div class="swiper-responsive-breakpoints swiper-container px-4 py-2">
                                <div class="swiper-wrapper">
									@foreach($related as $product)
                                    <div style="background-color: #fff" class="swiper-slide rounded swiper-shadow">
                                        <div class="item-heading">
                                            <p class="text-truncate mb-0">
											{{ $product->acName }}
                                            </p>
                                            <p>
                                                <small>{{ $product->brand->name }}</small>
                                            </p>
                                        </div>
                                        <div class="img-container w-50 mx-auto my-2 py-75">
                                            <img src="{{ $product->photo_medium }}" class="img-fluid" alt="{{ $product->name }}">
                                        </div>
                                        <div class="item-meta">
                                            <p class="text-primary mb-0">{{ format_price($product->price_discounted) }} {{ $currency }}</p>
                                        </div>
                                    </div>
									@endforeach
                                </div>
                                <!-- Add Arrows -->
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>

                            </div>
                        </div>
						@endif
                    </div>
                </section>
                <!-- app ecommerce details end -->
            </div>
    <!-- END: Content-->
@endsection

@section('script')
<script>
@if(can('view-document'))
function shopDocumentSelected() {
    document.location.reload();
}
@endif
@if(can('create-document'))
function shopDocumentCreated(response) {
    $('#form-modal1').modal('hide');
    loader_on();
    document.location.reload();
}
@endif
</script>
@endsection


@section('script-vendor')
<script src="{{ asset('assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js').assetVersion() }}"></script>
<script src="{{ asset('assets/app-assets/js/scripts/pages/app-ecommerce-shop.js').assetVersion() }}"></script>
<script src="{{ asset('assets/app-assets/js/scripts/forms/number-input.js').assetVersion() }}"></script>
<script src="{{ asset('assets/app-assets/js/scripts/pages/app-ecommerce-details.js').assetVersion() }}"></script>
<script src="{{ asset('assets/app-assets/vendors/js/extensions/swiper.min.js').assetVersion() }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>
@endsection
@section('css-vendor')
<link  href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>
<link href="{{ asset('assets/app-assets/css/pages/app-ecommerce-details.css').assetVersion() }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/app-assets/vendors/css/extensions/swiper.min.css').assetVersion() }}" rel="stylesheet" type="text/css">
@endsection

@section('css')
    @parent
    @include('shop._style')
@endsection
