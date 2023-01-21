<section id="ecommerce-products" class="list-view">
    @foreach($items as $item)
    <?php $scopedProduct = ScopedDocument::hasProduct($item->id); $contractProduct = ScopedContract::getProduct($item->id); $unit = is_null($item->rUnit) ? '' : $item->rUnit->name; ?>
    <div class="card ecommerce-card">
        <div class="card-content">
            
            <div class="item-img text-center pt-0">
                <a href="{{ url('shop/' . str_slug($item->name) . '/' . $item->id ) }}">
                    <img class="img-fluid" src="{{ asset('assets/img/noimage.jpeg') }}" alt="{{ $item->name }}">
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
                        {{ format_price($item->anSalePrie) }} {{ $currency }}
                    </h6>
                </div>
                <div class="item-name">
                    <a href="{{ url('shop/' . str_slug($item->name) . '/' . $item->id ) }}">{{ $item->acName }}</a>
                </div>
                <span class="code">Šifra: <span>{{ $item->acIdent }}</span></span>
                @if($item->barcode != '')
                <span class="code">Barcode: <span>{{ $item->acCode }}</span></span>
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
				<span class="stock-in">Zaliha: <span>{{ round($item->stock['anStock'], 8) }} {{ $unit }}</span></span>
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
                            MPC: {{ format_price($item->anSalePrice, 2) }} {{ $currency }}
                            <br>
                            <br>
                            VPC: {{ format_price($item->anWSPrice, 2) }} {{ $currency }}
                        </h6>
                    </div>
					<div class="item-rating">
                       
					</div>
                </div>
                {{--<div class="wishlist">
                    <!--<i class="fa fa-heart-o mr-25"></i> Wishlist-->
                </div>--}}
                <div class="qty">
                    <div class="">
               
                        <button type="button" class="btn btn-primary bootstrap-touchspin-down" style="padding: 0;min-width: 22px; min-height: 22px;border-radius: 5px !important;position: relative;">-</button>
                        <input type="text" value="1"  style="padding: 0;min-width: 22px; min-height: 22px;border-radius: 5px !important;position: relative; background: none;">
                        <button type="button" class="btn btn-primary bootstrap-touchspin-up"  style="padding: 0;min-width: 22px; min-height: 22px;border-radius: 5px !important;position: relative;">+</button>
                    </div>
                        
                        
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
