{!! Form::open(['url' => $form_url, 'method' => $method, 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$item->getTable()), 'data-callback' => request('callback')]) !!}
    {!! Form::hidden('user_id') !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong> <span class="badge badge-info text-uppercase">{{ $action->rType->name }}</span></p>
        <hr>
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="action-tab" data-toggle="tab" href="#action-tab-content" role="tab" aria-selected="true">{{ trans('action.vars.tabs.action') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="gratis-tab" data-toggle="tab" href="#gratis-tab-content" role="tab" aria-selected="false">{{ trans('action.vars.tabs.gratis') }}</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="action-tab-content" role="tabpanel">
                @include('action.product.form.action_products')
            </div>
            <div class="tab-pane active" id="action-tab-content" role="tabpanel">
            
            </div>
        </div>
        
        <div class="row">
            <div class="col-12" data-action-product-search>
                {!! VuexyAdmin::select('products_search', [], null, ['data-plugin-selectTwoAjax', 'data-plugin-options' => '{"placeholder": "'.trans('product.placeholders.search').'", "ajax": {"url": "'.route('product.search', ['s' => $action->stock_id, 'c' => $stock->country_id]).'", "type": "get"}}', 'id' => 'form-control-products_search-'.$item->getTable()], [], '') !!}
            </div>
            <div class="col-12">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ trans('action.data.products') }}</th>
                            <th class="text-center">{{ trans('action.data.stock_id') }}</th>
                            <th class="text-center">{{ trans('action.data.qty') }}</th>
                            <th class="text-center">{{ trans('action.data.vpc_price') }}</th>
                            <th class="text-center">{{ trans('action.data.mpc_price') }}</th>
                            @if($action->type_id == 'discount')
                            <th class="text-center">{{ trans('action.data.discount') }}</th>
                            @endif
                            <th class="text-right">{{ trans('skeleton.data.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody data-action-product-list>
                        @foreach($action_products as $product_id => $action_product)
                            @if(isset($products[$action_product['product_id']]))
                        <tr>@php $product = $products[$action_product['product_id']]; @endphp
                            <td>
                                {{ $product->name }}
                                <input type="hidden" name="u[]" value="{{ $action_product['product_id'] }}">
                            </td>
                            <td class="td-route">
                                {{ $qty = $product->getProductQuantities($action->stock_id)['qty'] }}
                                <input type="hidden" name="s[{{ $action_product['product_id'] }}]" value="{{ $qty }}">
                            </td>
                            <td class="td-route">
                                <input name="q[{{ $action_product['product_id'] }}]" type="text" class="form-control form-control-route" value="{{ $action_product['qty'] }}" min="1" aria-label="q" maxlength="6" autocomplete="off" data-plugin-autonumeric data-a-dec="," data-a-sep="." data-plugin-options='{"mDec": "0", "aSep": ""}'>
                            </td>
                            <td class="td-route">
                                {{ format_price($action_product->prices['vpc'], 2) }} {{ $stock->currency }}
                            </td>
                            <td class="td-route">
                                {{ format_price($action_product->prices['mpc'], 2) }} {{ $stock->currency }}
                            </td>
                            @if($action->type_id == 'discount')
                            <td class="td-route">
                                <input name="d[{{ $action_product['product_id'] }}]" type="text" class="form-control form-control-route" value="{{ $action_product['discount'] }}" aria-label="d" autocomplete="off" data-plugin-autonumeric data-a-dec="," data-a-sep=".">
                            </td>
                            @endif
                            <td class="td-actions">
                                <a title="{{ trans('client.actions.remove_product') }}" data-tooltip data-action-product-remove="{{ $action_product['product_id'] }}"><i class="feather icon-trash-2"></i></a>
                            </td>
                        </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <div class="no-results @if(empty($action_products)){{ 'show' }}@endif" data-action-product-no-results>
                    <h5>{{ trans('skeleton.no_results') }}</h5>
                </div>
            </div>
            @if($action->stock_type == 'unlimited')
            <div class="col-12">
                <hr>
                {!! VuexyAdmin::checkbox('change_qty', 1, empty($action_products), [], 'Promjeni zalihu akcije') !!}
            </div>
            @endif
            @if($action->isGratis())
            <div class="col-12" data-action-product-gratis>
                <hr>
                {!! VuexyAdmin::select('product_id', is_null($gratis_product) ? [] : [$gratis_product->id => $gratis_product->name], null, ['data-plugin-selectTwoAjax', 'data-plugin-options' => '{"placeholder": "'.trans('product.placeholders.search').'", "ajax": {"url": "'.route('product.search', ['s' => $action->stock_id, 'c' => $stock->country_id]).'", "type": "get"}}', 'id' => 'form-control-product_id-'.$item->getTable()], [], trans('action.data.product_id')) !!}
                <p style="margin-top: -10px">Cijena gratis proizvoda: <strong><span data-product-gratis-price>{{ format_price(0, 2) }}</span> {{ $stock->currency }}</strong></p>
            </div>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal">{{ trans('skeleton.actions.cancel') }}</button>
        <button class="btn btn-success" type="submit">{{ trans('skeleton.actions.submit') }}</button>
    </div>
{!! Form::close() !!}

<script id="action-product-template" type="text/x-custom-template">
    <tr>
        <td>
            @{{ text }}
            <input type="hidden" name="c[]" value="@{{ id }}">
        </td>
        <td class="td-route">
            @{{ qty }}
            <input type="hidden" name="s[@{{ id }}]" value="@{{ qty }}">
        </td>
        <td class="td-route">
            <input name="q[@{{ id }}]" type="text" class="form-control form-control-route" value="1" min="1" aria-label="q" maxlength="6" autocomplete="off" data-plugin-autonumeric data-a-dec="," data-a-sep="." data-plugin-options='{"mDec": "0", "aSep": ""}'>
        </td>
        <td class="td-route">
            @{{ prices.vpc }} {{ $stock->currency }}
        </td>
        <td class="td-route">
            @{{ prices.mpc }} {{ $stock->currency }}
        </td>
        @if($action->type_id == 'discount')
        <td class="td-route">
            <input name="d[@{{ id }}]" type="text" class="form-control form-control-route" value="0.00" aria-label="d" autocomplete="off" data-plugin-autonumeric data-a-dec="," data-a-sep=".">
        </td>
        @endif
        <td class="td-actions">
            <a title="{{ trans('client.actions.remove_product') }}" data-tooltip data-action-product-remove="@{{ id }}"><i class="feather icon-trash-2"></i></a>
        </td>
    </tr>
</script>

<script>
    $(document).ready(function () {
        
        
        @if($action->isGratis())
        select2ajax($('[data-action-product-gratis]'), {
            dropdownParent: $('.{{ $form_class }}').parent()
        });
    
        $('select[name="product_id"]').change(function() {
            // Check
            if ($(this).val() === null) {
                // Return
                return;
            }
            // Data
            var data = $(this).select2('data')[0];
            $('span[data-product-gratis-price]').text(data.price)
        });
        @endif
    });
    
</script>
