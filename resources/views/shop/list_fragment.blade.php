<section id="ecommerce-products" class="list-view">
    @foreach($items as $item)
    <?php $scopedProduct = ScopedDocument::hasProduct($item->id); $contractProduct = ScopedContract::getProduct($item->id); $unit = is_null($item->rUnit) ? '' : $item->rUnit->name; ?>
    <div class="card ecommerce-card">
        <div class="card-content">
            <div class="item-img text-center pt-0">
                <a href="{{ url('shop/' . str_slug($item->name) . '/' . $item->id ) }}">
                    <img class="img-fluid" src="{{ $item->photo_small }}" alt="{{ $item->name }}">
                </a>
            </div>
            <div class="card-body">
                <div class="item-wrapper">
					<div class="item-rating">
						<div class="badge badge-primary badge-md">
							<span>{{ $item->loyalty_points }}</span> <i class="feather icon-award"></i>
						</div>
					</div>
					@if($item->has_discount)
					<h6 class="item-price old">
						{{ format_price($item->price_old) }} {{ $currency }}
					</h6>
					@endif
                    <h6 class="item-price">
                        {{ format_price($item->price_discounted) }} {{ $currency }}
                    </h6>
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
                @if(!is_null($contractProduct))
                <span class="code"><span class="badge badge-info text-uppercase">Ugovoreno: {{ $contractProduct->qty }} {{ $unit }}</span> / <span class="badge badge-danger text-uppercase">Kupljeno: {{ $contractProduct->bought }} {{ $unit }}</span></span>
                @endif
                @if(!userIsClient())
				<span class="stock-in">Zaliha: <span>{{ $item->qty }} {{ $unit }}</span></span>
                @endif
				@if(isset($item->ordered))
				<span style="color: #7367F0" class="stock-in">Naručivano: <span>{{ $item->ordered }} {{ is_null($item->rUnit) ? '' : $item->rUnit->name }}</span></span>
				@endif
            </div>
            <div class="item-options">
                <div class="item-wrapper">
                    <div class="item-cost">
						@if($item->has_discount)
                        <h6 class="item-price old">
                            {{ format_price($item->price_old, 2) }} {{ $currency }}
                        </h6>
						@endif
                        <h6 class="item-price">
                            {{ format_price($item->price_discounted, 2) }} {{ $currency }}
                        </h6>
                    </div>
					<div class="item-rating">
                       
					</div>
                </div>
                {{--<div class="wishlist">
                    <!--<i class="fa fa-heart-o mr-25"></i> Wishlist-->
                </div>--}}
                <div class="qty">
                    @if(ScopedDocument::isReturn() || ScopedDocument::isPreOrder() || ($item->qty > 0))
                        @if(ScopedDocument::exist())
                        <div class="input-group quantity-counter-wrapper">
                            <input class="add-to-basket quantity-counter" type="number" autocomplete="off" data-max="{{ (ScopedDocument::isReturn() || ScopedDocument::isPreOrder()) ? 1000 : $item->qty }}" id="product_quantity_{{ $item->id }}" data-product-id="{{ $item->id }}" data-document-type="{{ ScopedDocument::typeId() }}" data-min="{{ $min_qty = ScopedDocument::getProductMinQty($item->id) }}" name="qty" placeholder="0" value="{{ isset($scopedProduct->qty) ? $scopedProduct->qty : '' }}">
                        </div>
                        @else
                        <input class="choose-document" type="number" name="qty" placeholder="0" min="0" value="0" title="Izaberi dokument" data-tooltip data-toggle="modal" data-target="#form-modal1" data-href="{{ route('document.draft.index') }}" readonly>
                        @endif
                    @else
                        Nema na zalihama
                    @endif
                </div>
                @if((isset($min_qty)) && ($min_qty > 0))
                <small class="text-center d-block"><strong>Min. količina {{ $min_qty }}</strong></small>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</section>
<!-- Ecommerce Products Ends -->

<!-- Ecommerce Pagination Starts -->
<section id="ecommerce-pagination">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="pagination">
               
            </nav>
        </div>
    </div>
</section>
<!-- Ecommerce Pagination Ends -->
