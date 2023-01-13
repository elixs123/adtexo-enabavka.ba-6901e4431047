@extends('layouts.app', ['scoped_footer' => false])

@section('head_title', $title = 'Checkout')

@section('content')
            <div class="content-body">
                <form action="/cart/finish" method="post" class="icons-tab-steps checkout-tab-steps wizard-circle">
                    {{ csrf_field() }}
                    <!-- Checkout Place order starts -->
                    <h6><i class="step-icon step feather icon-shopping-cart"></i>Korpa</h6>
                    <fieldset class="checkout-step-1 px-0">
                        <section id="place-order" class="list-view product-checkout">
                            <div class="checkout-items">
                                @foreach ($products as $item)
                                <div id="item-{{ $item->product_id }}" class="card ecommerce-card">
                                    <div class="card-content">
                                        <div class="item-img text-center">
											@if(!is_null($item->rProduct))
                                            <a href="{{ url('shop/' . str_slug($item->name) . '/' . $item->product_id) }}">
                                                <img class="img-fluid" src="{{ $item->rProduct->photo_medium }}" alt="{{ $item->name }}">
                                            </a>
											@endif
                                        </div>
                                        <div class="card-body">
                                            <div class="item-name">
                                                <a href="{{ url('shop/' . str_slug($item->name) . '/' . $item->product_id) }}">{{ $item->name }}</a>
                                                <span></span>
                                                <p class="item-company">Šifra: <span class="company-name">{{ $item->code }}</span></p>
                                                @if($document->isAction())
                                                <p class="item-company">Količina: <span class="company-name">{{ $item->qty }}</span></p>
                                                <p class="item-company">Akcija: <span class="company-name">{{ ScopedDocument::action()->name }}</span></p>
                                                @else
                                                <p class="stock-status-in">Na stanju</p>
                                                @endif
                                            </div>
                                            @if(!$document->isAction())
                                            <div class="item-quantity">
                                                <p class="quantity-title">Količina</p>
                                                <div class="input-group quantity-counter-wrapper">
                                                <input type="text" data-product-id="{{ $item->product_id }}" data-price="{{ $item->price_discounted }}" data-max="{{ $item->rProduct->qty }}" class="quantity-counter product-quantity" value="{{ $item->qty }}">
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="item-options text-center">
                                            <div class="item-wrapper">
                                                <div class="item-cost">
                                                    <h6 class="item-price">
                                                        <span id="item-total-{{ $item->product_id }}">{{ format_price($item->total_discounted_value, 2) }}</span> {{ $document->currency }}
                                                    </h6>
                                                    @if($item->discount3 > 0)
                                                    <p class="shipping">Rabat 3: <strong style="color: #626262;">{{ format_price($item->discount3, 2) }}%</strong></p>
                                                    @endif
                                                </div>
                                            </div>
                                            @if(!$document->isAction())
                                            <div data-product-id="{{ $item->product_id }}" class="wishlist remove-from-basket">
                                                <i class="feather icon-x align-middle"></i> Ukloni
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
							
								<div class="checkout-options">
									<div class="card">
										<div class="card-content">
                                            @if($products->count())
											<div class="card-body">
												<div id="quick-overview" data-quick-overview>@include("cart.{$summary_view}")</div>
												<button type="button" class="btn btn-primary btn-block place-order">Nastavi dalje</button>
											</div>
                                            @endif
										</div>
									</div>
								</div>
														
                        </section>
                    </fieldset>
                    <!-- Checkout Place order Ends -->

                    <!-- Checkout Customer Address Starts -->
                    <h6><i class="step-icon step feather icon-home"></i>{{ ($document->delivery_type == 'personal_takeover') ? 'Napomena' : 'Adresa' }}</h6>
                    <fieldset class="checkout-step-2 px-0">
                        <section id="checkout-address" class="list-view product-checkout">
                            <div class="card">
                                <div class="card-header flex-column align-items-start">
                                    @if($document->delivery_type == 'personal_takeover')
                                    <h4 class="card-title">Napomena</h4>
                                    <p class="text-muted mt-25">Unesite napomenu, ako je imate.</p>
                                    @else
                                    <h4 class="card-title">Adresa dostave</h4>
                                    <p class="text-muted mt-25">Unesite ispravne podatke o dostavi, jer ćemo na ovu adresu poslati vašu narudžbu.</p>
                                    @endif
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="row">
                                            @if($document->delivery_type != 'personal_takeover')
                                            <div class="col-md-6 col-sm-12">@php $responsiblePerson = $document->rClient->rResponsiblePerson; @endphp
                                                {!! VuexyAdmin::text('name', userIsSalesAgent() ? null : (is_null($responsiblePerson) ? '' : $responsiblePerson->name), ['maxlength' => 100, 'required', 'class' => 'form-control required'], 'Ime i prezime') !!}
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                {!! VuexyAdmin::email('email', userIsSalesAgent() ? null : (is_null($responsiblePerson) ? '' : $responsiblePerson->email), ['maxlength' => 100, 'required', 'class' => 'form-control required'], 'E-mail') !!}
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                {!! VuexyAdmin::text('phone', userIsSalesAgent() ? null : $document->rClient->phone, ['maxlength' => 20, 'required', 'class' => 'form-control required'], 'Telefon') !!}
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                {!! VuexyAdmin::text('address', userIsSalesAgent() ? null : $document->rClient->address, ['maxlength' => 100, 'required', 'class' => 'form-control required'], 'Adresa') !!}
                                            </div>
                                            <div class="col-md-6 col-sm-12">
												<input id="form-control-city" type="hidden" name="city" value="{{ userIsSalesAgent() ? null : $document->rClient->city }}" />
											<div id="form-group-city_id" class="form-group required" data-name="city_id">
												<label for="form-control-city_id">{{ trans('client.data.city') }}</label>
												<select id="form-control-city_id" class="form-control populate plugin-selectTwo" data-plugin-selectTwo required name="city_id">
												<option value="">Odaberite</option>
												@foreach($cities as $city)
												<option @if($document->rClient->postal_code == $city->postal_code && $document->rClient->city == $city->name && !userIsSalesAgent()) selected @endif value="{{ $city->postal_code }}">{{ $city->full_city }}</option>
												@endforeach
											</select>
											</div>
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                {!! VuexyAdmin::text('postal_code', userIsSalesAgent() ? null : $document->rClient->postal_code, ['readonly', 'maxlength' => 20, 'required', 'class' => 'form-control required'], 'Poštanski broj') !!}
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                {!! VuexyAdmin::text('country', $document->rClient->rCountry->name, ['maxlength' => 100, 'required', 'class' => 'form-control required', 'readonly'], 'Država') !!}
                                            </div>
                                            @else
                                            <input type="hidden" class="form-control required" value="1">
                                            @endif
                                            <div class="col-sm-12">
                                                {!! VuexyAdmin::textarea('note', $document->note, ['maxlength' => 150, 'class' => 'form-control', 'rows' => 2], 'Napomena za enabavka.ba') !!}
                                            </div>
                                            <div class="col-sm-12">
                                                {!! VuexyAdmin::textarea('note_express_post', $document->note_express_post, ['maxlength' => 150, 'class' => 'form-control', 'rows' => 2], 'Napomena za brzu poštu') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
								<div class="checkout-options">
									<div class="card">
										<div class="card-content">
											<div class="card-body">
												<div id="quick-overview" data-quick-overview>@include("cart.{$summary_view}")</div>
												<button type="button" class="btn btn-primary btn-block delivery-address">Nastavi dalje</button>
											</div>
										</div>
									</div>
								</div>
                        </section>
                    </fieldset>

                    <!-- Checkout Customer Address Ends -->

                    <!-- Checkout Payment Starts -->
                    <h6><i class="step-icon step feather icon-credit-card"></i>Plaćanje</h6>
                    <fieldset class="checkout-step-3 px-0">
                        <section id="checkout-payment" class="list-view product-checkout">
                            <div class="payment-type">
                                <div class="card">
                                    <div class="card-header flex-column align-items-start">
                                        <h4 class="card-title">Način plaćanja</h4>
                                        <p class="text-muted mt-25">Budite sigurni da ste odabrali željeni način plaćanja</p>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <ul class="other-payment-options list-unstyled">@php $options = (userIsClient() || ($document->payment_type == 'advance_payment')) ? get_codebook_opts('payment_type')->where('code', $document->payment_type) : get_codebook_opts('payment_type'); @endphp
                                                @foreach ($options as $payment_type)
                                                <li>
                                                    <div class="vs-radio-con vs-radio-primary py-25">
                                                        <input @if($document->payment_type == $payment_type->code) checked @endif type="radio" name="payment_type" value="{{ $payment_type->code }}">
                                                        <span class="vs-radio">
                                                            <span class="vs-radio--border"></span>
                                                            <span class="vs-radio--circle"></span>
                                                        </span>
                                                        <span>
                                                           {{ $payment_type->name }}
                                                        </span>
                                                    </div>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
								<div class="checkout-options">
									<div class="card">
										<div class="card-content">
											<div class="card-body">
												<div id="quick-overview" data-quick-overview>@include("cart.{$summary_view}")</div>
												<button type="submit" class="btn btn-primary btn-block finish-order">Završi narudžbu</button>
											</div>
										</div>
									</div>
								</div>
                        </section>
                    </fieldset>
                    <!-- Checkout Payment Starts -->
                </form>

            </div>
        </div>
    </div>
    <!-- END: Content-->
    @endsection

@section('css-vendor')
<link href="{{ asset('assets/app-assets/css/pages/app-ecommerce-shop.css').assetVersion() }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/app-assets/css/plugins/forms/wizard.css').assetVersion() }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/app-assets/css/plugins/extensions/toastr.css').assetVersion() }}" rel="stylesheet" type="text/css">
@endsection

@section('script-vendor')
<script src="{{ asset('assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js').assetVersion() }}" type="text/javascript"></script>
<script src="{{ asset('assets/app-assets/vendors/js/extensions/jquery.steps.min.js').assetVersion() }}" type="text/javascript"></script>
<script src="{{ asset('assets/app-assets/vendors/js/extensions/toastr.min.js').assetVersion() }}" type="text/javascript"></script>
<script src="{{ asset('assets/app-assets/js/scripts/pages/app-ecommerce-shop.js').assetVersion() }}" type="text/javascript"></script>
<script>
$(document).ready(function () {
	select2init($('.checkout-tab-steps'), {
		dropdownParent: $('.checkout-tab-steps').parent(),
	});
	// Change: Select City
	$('select[name="city_id"]').change(function(e) {
		$('#form-control-city').val($("#form-control-city_id option:selected").text().slice(0,-8));
		$('#form-control-postal_code').val($(this).val());
	});
 });
</script>
@endsection
