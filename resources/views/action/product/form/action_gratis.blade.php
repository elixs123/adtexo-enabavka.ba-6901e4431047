<div class="row">
    <div class="col-12" data-gratis-product-search>
        {!! VuexyAdmin::select('products_gratis', [], null, ['data-plugin-selectTwoAjax', 'data-plugin-options' => '{"placeholder": "'.trans('product.placeholders.search').'", "ajax": {"url": "'.route('product.search', ['s' => $action->stock_id, 'c' => $stock->country_id]).'", "type": "get"}}', 'id' => 'form-control-products_gratis-'.$item->getTable()], [], '') !!}
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
                    <th class="text-right">{{ trans('skeleton.data.actions') }}</th>
                </tr>
            </thead>
            <tbody data-gratis-product-list>
                @foreach($products_gratis as $product_id => $gratis_product)
                    @if(isset($products[$gratis_product['product_id']]))
                <tr>@php $product = $products[$gratis_product['product_id']]; @endphp
                    <td>
                        {{ $product->name }}
                        <input type="hidden" name="u[]" value="{{ $gratis_product['product_id'] }}">
                    </td>
                    <td class="td-route">
                        {{ $qty = $product->getProductQuantities($action->stock_id)['qty'] }}
                        <input type="hidden" name="s[gratis][{{ $gratis_product['product_id'] }}]" value="{{ $qty }}">
                    </td>
                    <td class="td-route">
                        <input name="q[gratis][{{ $gratis_product['product_id'] }}]" type="text" class="form-control form-control-route" value="{{ $gratis_product['qty'] }}" min="1" aria-label="q" maxlength="6" autocomplete="off" data-plugin-autonumeric data-a-dec="," data-a-sep="." data-plugin-options='{"mDec": "0", "aSep": ""}'>
                    </td>
                    <td class="td-route">
                        {{ format_price($gratis_product->prices['vpc'], 2) }} {{ $stock->currency }}
                    </td>
                    <td class="td-route">
                        {{ format_price($gratis_product->prices['mpc'], 2) }} {{ $stock->currency }}
                    </td>
                    <td class="td-actions">
                        <a title="{{ trans('client.actions.remove_product') }}" data-tooltip data-gratis-product-remove="{{ $gratis_product['product_id'] }}"><i class="feather icon-trash-2"></i></a>
                    </td>
                </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <div class="no-results @if(empty($products_gratis)){{ 'show' }}@endif" data-gratis-product-no-results>
            <h5>{{ trans('skeleton.no_results') }}</h5>
        </div>
    </div>
</div>

@section('modal-scripts')
    @parent
    <script id="gratis-product-template" type="text/x-custom-template">
        <tr>
            <td>
                @{{ text }}
                <input type="hidden" name="c[gratis][]" value="@{{ id }}">
            </td>
            <td class="td-route">
                @{{ qty }}
                <input type="hidden" name="s[gratis][@{{ id }}]" value="@{{ qty }}">
            </td>
            <td class="td-route">
                <input name="q[gratis][@{{ id }}]" type="text" class="form-control form-control-route" value="1" min="1" aria-label="q" maxlength="6" autocomplete="off" data-plugin-autonumeric data-a-dec="," data-a-sep="." data-plugin-options='{"mDec": "0", "aSep": ""}'>
            </td>
            <td class="td-route">
                @{{ prices.vpc }} {{ $stock->currency }}
            </td>
            <td class="td-route">
                @{{ prices.mpc }} {{ $stock->currency }}
            </td>
            <td class="td-actions">
                <a title="{{ trans('client.actions.remove_product') }}" data-tooltip data-gratis-product-remove="@{{ id }}"><i class="feather icon-trash-2"></i></a>
            </td>
        </tr>
    </script>

    <script>
        $(document).ready(function () {
            gratisProducts();
        });
        function gratisProducts() {
            // Selected products
            var products = {!! json_encode(collect($products_gratis)->pluck('product_id')->transform(function($id) {return intval($id);})->toArray()) !!};
            var product_num = {{ count($products_gratis) }};
            // Select2 plugin
            select2ajax($('[data-gratis-product-search]'), {
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
            $('select[name="products_gratis"]').change(function(e) {
                // Check
                if ($(this).val() === null) {
                    // Return
                    return;
                }
                // Data
                var data = $(this).select2('data')[0];
                data.key = product_num;
                // Template
                var template = $('#gratis-product-template').html();
                Mustache.parse(template);
                $('[data-gratis-product-list]').prepend(Mustache.render(template, data));
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
            $('body').on('click', 'a[data-gratis-product-remove]', function (e) {
                // Prevent default
                e.preventDefault();
                // Product
                var product_id = parseInt($(this).data('gratis-product-remove'));
                var index = products.indexOf(product_id);
                if (index > -1) {
                    products.splice(index, 1);
                    product_num--;
                    // Check
                    if (product_num <= 0) {
                        $('div[data-gratis-product-no-results]').addClass('show');
                        product_num = 0;
                    }
                    // Remove
                    $(this).parent().parent().replaceWith('<input type="hidden" name="r[gratis][]" value="' + product_id + '">');
                }
            })
        }
    </script>
@endsection
