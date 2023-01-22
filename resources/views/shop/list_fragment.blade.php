<section id="ecommerce-products" class="list-view">
    
    @foreach($items as $item)
    <?php $scopedProduct = ScopedDocument::hasProduct($item->id); $contractProduct = ScopedContract::getProduct($item->id); $unit = is_null($item->rUnit) ? '' : $item->rUnit->name; ?>
    <div class="card ecommerce-card">
        <div class="card-content">
            
            <div class="item-img text-center pt-0">
               
                    <img class="img-fluid" src="{{ asset('assets/img/noimage.jpeg') }}" alt="{{ $item->name }}">
                
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
                    {{ $item->acName }}
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
                    @if(count($order))
                        @if($item->stock['anStock'] > 0)
                        <div class="">
                            <form action="{{route('addToShop')}}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="acIdent" value="{{$item->acIdent}}">
                                <input type="hidden" name="anRTPrice" value="{{$item->anRTPrice}}">
                                <input type="hidden" name="anWSPrice2" value="{{$item->anWSPrice2}}">
                                <input type="hidden" name="acWayOfSale" value="Z">
                                <input type="hidden" name="anRebate1" value="0">
                                <input type="hidden" name="orderNumber" value="{{$order->orderNumber}}">
                                <input type="hidden" name="anRebate2" value="0">
                                <input type="hidden" name="anRebate3" value="0">
                            
                                <input type="hidden" name="anNo">
                                <button type="submit" class="btn btn-primary bootstrap-touchspin-down" style="padding: 0;min-width: 22px; min-height: 22px;border-radius: 5px !important;position: relative;" name="buttonMinus">-</button>
                                <input type="text" value="1" name="anQty"  style="padding: 0;min-width: 22px; min-height: 22px;border-radius: 5px !important;position: relative; background: none;">
                                <button type="submit" class="btn btn-primary bootstrap-touchspin-up"  style="padding: 0;min-width: 22px; min-height: 22px;border-radius: 5px !important;position: relative;" name="buttonPlus">+</button>
                            </form>
                        </div>
                        @else
                        <div>
                            <h4>Nema na zalihi</h4>
                        </div>
                        @endif
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
