{!! Form::open(['url' => $form_url, 'method' => $method, 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$item->getTable()), 'data-callback' => request('callback')]) !!}
    {!! Form::hidden('user_id') !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
        <hr>
        <div class="row">
            <div class="col-12" data-contract-product-search>
                {!! VuexyAdmin::select('products_search', [], null, ['data-plugin-selectTwoAjax', 'data-plugin-options' => '{"placeholder": "'.trans('product.placeholders.search').'", "ajax": {"url": "'.route('product.search').'", "type": "get"}}', 'id' => 'form-control-products_search-'.$item->getTable()], [], '') !!}
            </div>
            <div class="col-12">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ trans('contract.data.products') }}</th>
                            <th class="text-center">{{ trans('contract.data.qty') }}</th>
                            <th class="text-center">{{ trans('contract.data.discount') }}</th>
                            <th class="text-right">{{ trans('skeleton.data.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody data-contract-product-list>
                        @foreach($contract_products as $key => $contract_product)
                            @if(isset($products[$contract_product['product_id']]))
                        <tr>@php $product = $products[$contract_product['product_id']]; @endphp
                            <td>
                                {{ $product->name }}
                                <input type="hidden" name="u[]" value="{{ $contract_product['product_id'] }}">
                            </td>
                            <td class="td-route">
                                <input name="q[{{ $contract_product['product_id'] }}]" type="text" class="form-control form-control-route" value="{{ $contract_product['qty'] }}" aria-label="q" maxlength="6" autocomplete="off" data-plugin-autonumeric data-a-dec="," data-a-sep="." data-plugin-options='{"mDec": "0", "aSep": ""}'>
                            </td>
                            <td class="td-route">
                                <input name="d[{{ $contract_product['product_id'] }}]" type="text" class="form-control form-control-route" value="{{ $contract_product['discount'] }}" aria-label="d" autocomplete="off" data-plugin-autonumeric data-a-dec="," data-a-sep=".">
                            </td>
                            <td class="td-actions">
                                <a title="{{ trans('client.actions.remove_product') }}" data-tooltip data-contract-product-remove="{{ $contract_product['product_id'] }}"><i class="feather icon-trash-2"></i></a>
                            </td>
                        </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <div class="no-results @if(empty($contract_products)){{ 'show' }}@endif" data-contract-product-no-results>
                    <h5>{{ trans('skeleton.no_results') }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal">{{ trans('skeleton.actions.cancel') }}</button>
        <button class="btn btn-success" type="submit">{{ trans('skeleton.actions.submit') }}</button>
    </div>
{!! Form::close() !!}

<script id="contract-product-template" type="text/x-custom-template">
    <tr>
        <td>
            @{{ text }}
            <input type="hidden" name="c[]" value="@{{ id }}">
        </td>
        <td class="td-route">
            <input name="q[@{{ id }}]" type="text" class="form-control form-control-route" value="0" aria-label="q" maxlength="6" autocomplete="off" data-plugin-autonumeric data-a-dec="," data-a-sep="." data-plugin-options='{"mDec": "0", "aSep": ""}'>
        </td>
        <td class="td-route">
            <input name="d[@{{ id }}]" type="text" class="form-control form-control-route" value="0.00" aria-label="d" autocomplete="off" data-plugin-autonumeric data-a-dec="," data-a-sep=".">
        </td>
        <td class="td-actions">
            <a title="{{ trans('client.actions.remove_product') }}" data-tooltip data-contract-product-remove="@{{ id }}"><i class="feather icon-trash-2"></i></a>
        </td>
    </tr>
</script>

<script>
    $(document).ready(function () {
        App.validate('.{{ $form_class }}', {
            submitHandler: function(form) {
                AjaxForm.init('.{{ $form_class }}');
            }
        });
        autoNumericInit($('.{{ $form_class }}'), {});
        contractProducts();
    });
    function contractProducts() {
        // Selected products
        var products = {!! json_encode(collect($contract_products)->pluck('product_id')->transform(function($id) {return intval($id);})->toArray()) !!};
        var product_num = {{ count($contract_products) }};
        // Select2 plugin
        select2ajax($('[data-contract-product-search]'), {
            dropdownParent: $('.{{ $form_class }}').parent(),
            ajax: {
                data: function (params) {
                    return {
                        q: params.term,
                        e: products.join('.')
                    };
                },
                cache: false
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
            var template = $('#contract-product-template').html();
            Mustache.parse(template);
            $('[data-contract-product-list]').prepend(Mustache.render(template, data));
            // Product
            products.push(parseInt(data.id));
            product_num++;
            // Reset
            $(this).val(null).trigger('change');
            // Check
            if (product_num > 0) {
                $('div[data-contract-product-no-results]').removeClass('show');
            }
        });
        // Remove
        $('body').on('click', 'a[data-contract-product-remove]', function (e) {
            // Prevent default
            e.preventDefault();
            // Product
            var product_id = parseInt($(this).data('contract-product-remove'));
            var index = products.indexOf(product_id);
            if (index > -1) {
                products.splice(index, 1);
                product_num--;
                // Check
                if (product_num <= 0) {
                    $('div[data-contract-product-no-results]').addClass('show');
                    product_num = 0;
                }
                // Remove
                $(this).parent().parent().replaceWith('<input type="hidden" name="r[]" value="' + product_id + '">');
            }
        })
    }
</script>
