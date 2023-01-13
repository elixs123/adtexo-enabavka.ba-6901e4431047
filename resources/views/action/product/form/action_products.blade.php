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
                    @if($action->isDiscount())
                    <th class="text-center">{{ trans('action.data.vpc_discount') }}</th>
                    @endif
                    <th class="text-center">{{ trans('action.data.mpc_price') }}</th>
                    @if($action->isDiscount())
                    <th class="text-center">{{ trans('action.data.mpc_discount') }}</th>
                    @endif
                    <th class="text-right">&nbsp;</th>
                </tr>
            </thead>
            <tbody data-action-product-list>
                @foreach($products_action as $product_id => $action_product)
                    @if(isset($products[$action_product['product_id']]))
                <tr>@php $product = $products[$action_product['product_id']]; @endphp
                    <td>
                        {{ $product->name }}
                        <input type="hidden" name="u[action][]" value="{{ $action_product['product_id'] }}">
                    </td>
                    <td class="td-route">
                        {{ $qty = $product->getProductQuantities($action->stock_id)['qty'] }}
                        <input type="hidden" name="s[action][{{ $action_product['product_id'] }}]" value="{{ $qty }}">
                    </td>
                    <td class="td-route">
                        <input name="q[action][{{ $action_product['product_id'] }}]" type="text" class="form-control form-control-route" value="{{ $action_product['qty'] }}" min="1" aria-label="q" maxlength="6" autocomplete="off" data-plugin-autonumeric data-a-dec="," data-a-sep="." data-plugin-options='{"mDec": "0", "aSep": ""}'>
                    </td>
                    <td class="td-route">
                        {{ format_price($action_product->prices['vpc'], 2) }} {{ $stock->currency }}
                    </td>
                    @if($action->isDiscount())
                    <td class="td-route">
                        <input name="d[action][{{ $action_product['product_id'] }}][vpc]" type="text" class="form-control form-control-route" value="{{ $action_product['vpc_discount'] }}" aria-label="d" autocomplete="off" data-plugin-autonumeric data-a-dec="," data-a-sep=".">
                    </td>
                    @endif
                    <td class="td-route">
                        {{ format_price($action_product->prices['mpc'], 2) }} {{ $stock->currency }}
                    </td>
                    @if($action->isDiscount())
                    <td class="td-route">
                        <input name="d[action][{{ $action_product['product_id'] }}][mpc]" type="text" class="form-control form-control-route" value="{{ $action_product['mpc_discount'] }}" aria-label="d" autocomplete="off" data-plugin-autonumeric data-a-dec="," data-a-sep=".">
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
        <div class="no-results @if(empty($products_action)){{ 'show' }}@endif" data-action-product-no-results>
            <h5>{{ trans('skeleton.no_results') }}</h5>
        </div>
    </div>
</div>

@section('modal-scripts')
    @parent
    <script id="action-product-template" type="text/x-custom-template">
        <tr>
            <td>
                @{{ text }}
                <input type="hidden" name="c[action][]" value="@{{ id }}">
            </td>
            <td class="td-route">
                @{{ qty }}
                <input type="hidden" name="s[action][@{{ id }}]" value="@{{ qty }}">
            </td>
            <td class="td-route">
                <input name="q[action][@{{ id }}]" type="text" class="form-control form-control-route" value="1" min="1" aria-label="q" maxlength="6" autocomplete="off" data-plugin-autonumeric data-a-dec="," data-a-sep="." data-plugin-options='{"mDec": "0", "aSep": ""}'>
            </td>
            <td class="td-route">
                @{{ prices.vpc }} {{ $stock->currency }}
            </td>
            @if($action->isDiscount())
            <td class="td-route">
                <input name="d[action][@{{ id }}][vpc]" type="text" class="form-control form-control-route" value="0.00" aria-label="d" autocomplete="off" data-plugin-autonumeric data-a-dec="," data-a-sep=".">
            </td>
            @endif
            <td class="td-route">
                @{{ prices.mpc }} {{ $stock->currency }}
            </td>
            @if($action->isDiscount())
            <td class="td-route">
                <input name="d[action][@{{ id }}][mpc]" type="text" class="form-control form-control-route" value="0.00" aria-label="d" autocomplete="off" data-plugin-autonumeric data-a-dec="," data-a-sep=".">
            </td>
            @endif
            <td class="td-actions">
                <a title="{{ trans('client.actions.remove_product') }}" data-tooltip data-action-product-remove="@{{ id }}"><i class="feather icon-trash-2"></i></a>
            </td>
        </tr>
    </script>

    <script>
        $(document).ready(function () {
            actionProducts();
        });
        function actionProducts() {
            // Selected products
            var products = {!! json_encode(collect($products_action)->pluck('product_id')->transform(function($id) {return intval($id);})->toArray()) !!};
            var product_num = {{ count($products_action) }};
            // Select2 plugin
            select2ajax($('[data-action-product-search]'), {
                dropdownParent: $('.{{ $form_class }}').parent(),
                ajax: {
                    data: function (params) {
                        return {
                            q: params.term,
                            e: products.join('.')
                        };
                    },
                    cache: false,
                    processResults: function (data) {
                        var items = [];
                        data.items.forEach(function(item) {
                            if (item.qty > 0) {
                                items.push(item);
                            }
                        })
                
                        return {
                            results: items
                        };
                    }
                }
            });
            // Change: Select
            $('select[name="products_search"]').change(function(e) {
                // Check
                if ($(this).val() === null) {
                    // Return
                    return;
                }
                // Data
                var data = $(this).select2('data')[0];
                data.key = product_num;
                // Template
                var template = $('#action-product-template').html();
                Mustache.parse(template);
                $('[data-action-product-list]').prepend(Mustache.render(template, data));
                // Product
                products.push(parseInt(data.id));
                product_num++;
                // Reset
                $(this).val(null).trigger('change');
                // Check
                if (product_num > 0) {
                    $('div[data-action-product-no-results]').removeClass('show');
                }
            });
            // Remove
            $('body').on('click', 'a[data-action-product-remove]', function (e) {
                // Prevent default
                e.preventDefault();
                // Product
                var product_id = parseInt($(this).data('action-product-remove'));
                var index = products.indexOf(product_id);
                if (index > -1) {
                    products.splice(index, 1);
                    product_num--;
                    // Check
                    if (product_num <= 0) {
                        $('div[data-action-product-no-results]').addClass('show');
                        product_num = 0;
                    }
                    // Remove
                    $(this).parent().parent().replaceWith('<input type="hidden" name="r[action][]" value="' + product_id + '">');
                }
            })
        }
    </script>
@endsection

