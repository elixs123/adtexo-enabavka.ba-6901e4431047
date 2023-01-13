{!! Form::model($item, ['url' => $form_url, 'method' => $method, 'files' => true, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$item->getTable())]) !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
        <hr>
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
            <a class="nav-link active" id="main-tab" data-toggle="tab" href="#main-content" aria-controls="main" role="tab" aria-selected="true">{{ trans('product.data.general') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="dimensions-tab" data-toggle="tab" href="#dimensions-content" aria-controls="dimensions" role="tab" aria-selected="false">{{ trans('product.data.dimensions') }}</a>
            </li>
			@if(isset($item->product_id))
            <li class="nav-item">
                <a class="nav-link" id="gallery-tab" data-toggle="tab" href="#gallery-content" aria-controls="gallery" role="tab" aria-selected="false">Galerija</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="price-tab" data-toggle="tab" href="#price-content" aria-controls="price" role="tab" aria-selected="false">{{ trans('product.data.prices') }}</a>
            </li>
			@endif
            <li class="nav-item">
                <a class="nav-link" id="loyalty-tab" data-toggle="tab" href="#loyalty-content" aria-controls="loyalty" role="tab" aria-selected="false">{{ trans('product.data.program_loayalty') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="related-tab" data-toggle="tab" href="#related-content" aria-controls="related" role="tab" aria-selected="false">{{ trans('product.data.related') }}</a>
            </li>
            @if($item->id > 0)
            <li class="nav-item">
                <a class="nav-link" id="qty-tab" data-toggle="tab" href="#qty-content" aria-controls="qty" role="tab" aria-selected="false">{{ trans('product.data.quantities') }}</a>
            </li>
            @endif
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="main-content" aria-labelledby="main-tab" role="tabpanel">
                <div class="row">
                    <div class="col-12">
                        {!! VuexyAdmin::selectTwo('translation[lang_id]', config('app.locales'),  $item->lang_id, ['required', 'data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-status-'.$item->getTable()], trans('skeleton.lang')) !!}
                    </div>
                    <div class="col-12">
                        {!! VuexyAdmin::text('translation[name]', $item->name, ['maxlength' => 100, 'required'], trans('product.data.name')) !!}
                    </div>
                    <div class="col-12 col-sm-6">
                        {!! VuexyAdmin::text('item[code]', $item->code, ['maxlength' => 50, 'required'], trans('product.data.code')) !!}
                    </div>
                    <div class="col-12 col-sm-6">
                        {!! VuexyAdmin::text('item[barcode]', $item->barcode, ['maxlength' => 20], trans('product.data.barcode')) !!}
                    </div>
                    <div class="col-6">
                        {!! VuexyAdmin::selectTwo('item[brand_id]', $brands->pluck('name', 'id')->prepend('Choose', '')->toArray(), $item->brand_id, ['required', 'data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-brand-'.$item->getTable()], trans('product.data.brand')) !!}
                    </div>
                    <div class="col-6">
                        {!! VuexyAdmin::selectTwo('item[category_id]', $categories->pluck('name_length', 'id')->prepend('Choose', '')->toArray(), $item->category_id, ['required', 'data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-category-'.$item->getTable()], trans('product.data.category')) !!}
                    </div>
                    <div class="col-12">
                        {!! VuexyAdmin::file('photo', ['path' => config('picture.product_path').'/medium_'], trans('product.data.photo'), trans('skeleton.allowed_extensions', ['ext' => 'JPG'])) !!}
                    </div>
                    <div class="col-12">
                        {!! VuexyAdmin::text('item[video]', $item->video, ['maxlength' => 200], trans('product.data.video')) !!}
                    </div>
                    <div class="col-12">
                        {!! VuexyAdmin::textarea('translation[text]', $item->text, ['maxlength' => 5000], trans('product.data.text')) !!}
                    </div>
                    <div class="col-6">
                        {!! VuexyAdmin::selectTwo('item[status]', get_codebook_opts('status')->pluck('name', 'code')->toArray(), $item->status, ['required', 'data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-status-'.$item->getTable()], trans('skeleton.data.status')) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! VuexyAdmin::number('item[rang]', is_null($item->rang) ? 1 : $item->rang, ['maxlength' => 5, 'required'], trans('product.data.rang')) !!}
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="dimensions-content" aria-labelledby="dimensions-tab" role="tabpanel">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        {!! VuexyAdmin::selectTwo('item[unit_id]', get_codebook_opts('unit_types')->pluck('name', 'code')->toArray(), $item->unit_id, ['required', 'data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-unit_id-'.$item->getTable()], trans('product.data.unit_id')) !!}
                    </div>
                    <div class="col-12 col-sm-6">
                        {!! VuexyAdmin::text('item[packing]', $item->length, ['maxlength' => 20], trans('product.data.packing')) !!}
                    </div>
                    <div class="col-12 col-sm-6">
                        {!! VuexyAdmin::text('item[transport_packaging]', $item->width, ['maxlength' => 20], trans('product.data.transport_packaging')) !!}
                    </div>
                    <div class="col-12 col-sm-6">
                        {!! VuexyAdmin::text('item[palette]', $item->height, ['maxlength' => 20], trans('product.data.palette')) !!}
                    </div>
                    <div class="col-12 col-sm-6">
                        {!! VuexyAdmin::number('item[weight]', $item->weight, ['maxlength' => 5], trans('product.data.weight')) !!}
                    </div>
                    <div class="col-12 col-sm-6">
                        {!! VuexyAdmin::number('item[length]', $item->length, ['maxlength' => 5], trans('product.data.length')) !!}
                    </div>
                    <div class="col-12 col-sm-6">
                        {!! VuexyAdmin::number('item[width]', $item->width, ['maxlength' => 5], trans('product.data.width')) !!}
                    </div>
                    <div class="col-12 col-sm-6">
                        {!! VuexyAdmin::number('item[height]', $item->height, ['maxlength' => 5], trans('product.data.height')) !!}
                    </div>
                 </div>
            </div>
            <div class="tab-pane" id="loyalty-content" aria-labelledby="loyalty-tab" role="tabpanel">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        {!! VuexyAdmin::number('item[loyalty_points]', is_null($item->loyalty_points) ? 0 : $item->loyalty_points, ['maxlength' => 5, 'required'], trans('product.data.points')) !!}
                    </div>
                    <div class="col-12 col-sm-6">
                        {!! VuexyAdmin::selectTwo('item[is_gratis]', [0 => 'Ne', 1 =>'Da'], $item->is_gratis, ['required', 'data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-is_gratis-'.$item->getTable()], trans('product.data.is_gratis')) !!}
                    </div>
                </div>
            </div>
            @if(isset($item->product_id))
            <div class="tab-pane" id="price-content" aria-labelledby="price-tab" role="tabpanel">
                @foreach(['bih' => 'BiH', 'srb' => 'Srbija'] as $lang_id => $lang)
                <?php $price = $item->rProductPrices->where('country_id', $lang_id)->first(); ?>
                <h4>{{ $lang }}</h4>

                <div class="row">
                    <div class="col-12 col-sm-4">
                        {!! VuexyAdmin::text('prices['.$lang_id.'][mpc]', format_price($price ? $price->mpc : 0, 2), ['data-a-dec' => ",", 'data-a-sep' => ".", 'maxlength' => 10, 'required', 'class' => 'form-control', 'readonly'], trans('product.data.price_mpc')) !!}
                    </div>
                    <div class="col-12 col-sm-4">
                        {!! VuexyAdmin::text('prices['.$lang_id.'][mpc_old]', format_price($price ? $price->mpc_old : 0, 2), ['data-a-dec' => ",", 'data-a-sep' => ".", 'maxlength' => 10, 'required', 'class' => 'form-control', 'readonly'], trans('product.data.price_mpc_old')) !!}
                    </div>
                    <div class="col-12 col-sm-4">
                        {!! VuexyAdmin::text('prices['.$lang_id.'][mpc_discount]', format_price($price ? $price->mpc_discount : 0, 2), ['data-a-dec' => ",", 'data-a-sep' => ".", 'maxlength' => 10, 'required', 'class' => 'form-control', 'readonly'], trans('product.data.mpc_discount')) !!}
                    </div>
                    
                    <div class="col-12 col-sm-4">
                        {!! VuexyAdmin::text('prices['.$lang_id.'][vpc]', format_price($price ? $price->vpc : 0, 2), ['data-a-dec' => ",", 'data-a-sep' => ".", 'maxlength' => 10, 'required', 'class' => 'form-control', 'readonly'], trans('product.data.price_vpc')) !!}
                    </div>
                    <div class="col-12 col-sm-4">
                        {!! VuexyAdmin::text('prices['.$lang_id.'][vpc_old]', format_price($price ? $price->vpc_old : 0, 2), ['data-a-dec' => ",", 'data-a-sep' => ".", 'maxlength' => 10, 'required', 'class' => 'form-control', 'readonly'], trans('product.data.price_vpc_old')) !!}
                    </div>
                    <div class="col-12 col-sm-4">
                        {!! VuexyAdmin::text('prices['.$lang_id.'][vpc_discount]', format_price($price ? $price->vpc_discount : 0, 2), ['data-a-dec' => ",", 'data-a-sep' => ".", 'maxlength' => 10, 'required', 'class' => 'form-control', 'readonly'], trans('product.data.vpc_discount')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-4">
                        {!! VuexyAdmin::text('prices['.$lang_id.'][mpc_eur]', format_price($price ? $price->mpc_eur : 0, 2), ['data-a-dec' => ",", 'data-a-sep' => ".", 'maxlength' => 10, 'required', 'class' => 'form-control', 'readonly'], trans('product.data.price_mpc_eur')) !!}
                    </div>
                    <div class="col-12 col-sm-4">
                        {!! VuexyAdmin::text('prices['.$lang_id.'][mpc_eur_old]', format_price($price ? $price->mpc_eur_old : 0, 2), ['data-a-dec' => ",", 'data-a-sep' => ".", 'maxlength' => 10, 'required', 'class' => 'form-control', 'readonly'], trans('product.data.price_mpc_eur_old')) !!}
                    </div>
                    <div class="col-12 col-sm-4">
                        {!! VuexyAdmin::text('prices['.$lang_id.'][mpc_eur_discount]', format_price($price ? $price->mpc_eur_discount : 0, 2), ['data-a-dec' => ",", 'data-a-sep' => ".", 'maxlength' => 10, 'required', 'class' => 'form-control', 'readonly'], trans('product.data.mpc_eur_discount')) !!}
                    </div>
                    
                    <div class="col-12 col-sm-4">
                        {!! VuexyAdmin::text('prices['.$lang_id.'][vpc_eur]', format_price($price ? $price->vpc_eur : 0, 2), ['data-a-dec' => ",", 'data-a-sep' => ".", 'maxlength' => 10, 'required', 'class' => 'form-control', 'readonly'], trans('product.data.price_vpc_eur')) !!}
                    </div>
                    <div class="col-12 col-sm-4">
                        {!! VuexyAdmin::text('prices['.$lang_id.'][vpc_eur_old]', format_price($price ? $price->vpc_eur_old : 0, 2), ['data-a-dec' => ",", 'data-a-sep' => ".", 'maxlength' => 10, 'required', 'class' => 'form-control', 'readonly'], trans('product.data.price_vpc_eur_old')) !!}
                    </div>
                    <div class="col-12 col-sm-4">
                        {!! VuexyAdmin::text('prices['.$lang_id.'][vpc_eur_discount]', format_price($price ? $price->vpc_eur_discount : 0, 2), ['data-a-dec' => ",", 'data-a-sep' => ".", 'maxlength' => 10, 'required', 'class' => 'form-control', 'readonly'], trans('product.data.vpc_eur_discount')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        {!! VuexyAdmin::selectTwo('prices['.$lang_id.'][badge_id]', get_codebook_opts('product_badges')->pluck('name', 'code')->prepend(trans('product.data.no_badge'))->toArray(), isset($price->badge_id) ? $price->badge_id : null, [ 'data-plugin-options' => '{}', 'id' => 'form-control-badge_id-'.$lang_id.'-'.$item->getTable()], trans('product.data.bagde_id')) !!}
                    </div>
                </div>
                @endforeach
            </div>
            @endif
            <div class="tab-pane" id="related-content" aria-labelledby="related-tab" role="tabpanel">
                <div class="row">
                    <div class="col-12">
                        {!! VuexyAdmin::selectTwoAjax('item[related][]', isset($related) ? $related->toArray() : [], null, ['required', 'data-plugin-options' => '{"placeholder": "'.trans('product.placeholders.search').'", "ajax": {"url": "'.route('product.search').'", "type": "get"}}', 'id' => 'form-control-related-'.$item->getTable()], trans('product.data.related')) !!}
                    </div>
                    @if($item->is_promo_product)
                    <div class="col-12">
                        <div class="form-group">
                            <label>PROMO artikli</label>
                        </div>
                        <div class="table-responsive-lg">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <td>{{ trans('product.data.code') }}</td>
                                        <td>{{ trans('product.data.name') }}</td>
                                        <td class="text-center">{{ trans('product.data.qty') }}</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($promo_products as $promo_product)
                                    <tr>
                                        <td>{{ $promo_product->code }}</td>
                                        <td>{{ $promo_product->translation->name }}</td>
                                        <td class="text-center">{{ (int) $promo_product->pivot->promo_qty }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
			@if(isset($item->product_id))
            <div class="tab-pane" id="gallery-content" aria-labelledby="gallery-tab" role="tabpanel">
                <div class="row">
					<div class="col-12">
						<!-- Photo upload using jquery file upload plugin -->
						<?php $photo_upload_config = array(
							'item_id' => isset($item->product_id) ? $item->product_id : 0,
							'folder' => 'gallery',
							'modal_name' => 'photo_upload_modal_gallery',
							'item_photos' => isset($gallery) ? $gallery : array(),
							'load_resource' => true,
							'info' => ''
							);
						?>

						@include('partials.photos_upload_modal')
					</div>
                </div>
            </div>
			@endif
            @if($item->id > 0)
            <div class="tab-pane" id="qty-content" aria-labelledby="qty-tab" role="tabpanel">
                
                @if(isset($product_quantities) && count($product_quantities) > 0)
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div style="margin-bottom: 20px" id="total-qty" class="btn btn-info">{{ trans('product.data.current_qty') }}: <span>{{ $product_quantities->sum('qty') }}</span></div>
                        </div>

                        <div class="col-12 col-md-6">
                            <a class="btn btn-success" href="{{ route('product-stock.create') }}?product_id={{ $item->id }}" data-toggle="modal" data-target="#form-modal2">{{ trans('product.data.add_new_qty') }}</a>
                        </div>
                    </div>

                <h4>{{ trans('product.data.qty_per_stocks') }}</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ trans('product.data.stock') }}</th>
                            <th>{{ trans('product.data.qty') }}</th>
                        </tr>
                    </thead>
                <tbody>
                    @foreach ($product_quantities as $product_quantity)
                    <thead>
                        <tr>
                            <td>{{ $product_quantity->rStock->name }}</td>
                            <td>{{ $product_quantity->qty }}</td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
                </div>
            @endif
            
           
                @if(count($product_stocks = $item->rProductStocks()->with('rStock')->limit(10)->latest()->get()))
                <div class="col-12">
                <h4 class="pull-left">{{ trans('product.data.history') }}</h4>
                <p class="pull-right"><a href="{{ route('product.stocks', ['id' => $item->id]) }}">Sve izmjene</a></p>
                <div class="table-responsive-lg">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ trans('product.data.date') }}</th>
                                <th>{{ trans('product.data.stock') }}</th>
                                <th>{{ trans('product.data.qty') }}</th>
                                <th>{{ trans('skeleton.data.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody data-ajax-form-body="product_stocks">
                            @foreach ($product_stocks as $qty)
                            @include('product.stock._row', ['item' => $qty, 'modal' => '#form-modal2'])
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            </div>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal">{{ trans('skeleton.actions.cancel') }}</button>
        <button class="btn btn-success" type="submit">{{ trans('skeleton.actions.submit') }}</button>
    </div>
    @if(isset($item->id))
        <input type="hidden" name="translation[product_id]" value="{{ $item->id }}">
    @endif
{!! Form::close() !!}

<script>

    function updateStockQty(response)
    {
        $('#total-qty span').text(response.stock);
    }

    $(document).ready(function () {

        App.tooltip();
		
		$('.autonumeric').autoNumeric('init', { vMin: 0, mDec: 2 });

        App.validate('.{{ $form_class }}', {
            submitHandler: function(form) {
                AjaxForm.init('.{{ $form_class }}');
            }
        });
        select2init($('.{{ $form_class }}'), {
            dropdownParent: $('.{{ $form_class }}').parent(),
        });
		
		select2ajax($('.{{ $form_class }}'), {
            dropdownParent: $('.{{ $form_class }}').parent(),
			multiple:true,
        });
    });
</script>
