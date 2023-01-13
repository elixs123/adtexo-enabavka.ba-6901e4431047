<div class="row">
    <div class="col-12" data-client-product-search>
        {!! VuexyAdmin::select('products_search', [], null, ['data-plugin-selectTwoAjax', 'data-plugin-options' => '{"placeholder": "'.trans('product.placeholders.search').'", "ajax": {"url": "'.route('product.search').'", "type": "get"}}', 'id' => 'form-control-products_search-'.$item->getTable()], [], '') !!}
    </div>
    <div class="col-12">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>{{ trans('client.data.products') }}</th>
                    <th class="text-right">{{ trans('skeleton.data.actions') }}</th>
                </tr>
            </thead>
            <tbody data-client-product-list>
                @foreach($products_selected as $key => $product)
                <tr>
                    <td>
                        {{ $product['name'] }}
                        <input type="hidden" name="products[]" value="{{ $product['id'] }}">
                    </td>
                    <td class="td-actions">
                        <a title="{{ trans('client.actions.remove_product') }}" data-tooltip data-client-product-remove="{{ $product['id'] }}"><i class="feather icon-trash-2"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <div class="no-results @if(empty($products_selected)){{ 'show' }}@endif" data-client-product-no-results>
            <h5>{{ trans('skeleton.no_results') }}</h5>
        </div>
    </div>
</div>

<script id="client-product-template" type="text/x-custom-template">
    <tr>
        <td>
            @{{ text }}
            <input type="hidden" name="products[]" value="@{{ id }}">
        </td>
        <td class="td-actions">
            <a title="{{ trans('client.actions.remove_product') }}" data-tooltip data-client-product-remove="@{{ id }}"><i class="feather icon-trash-2"></i></a>
        </td>
    </tr>
</script>