{!! Form::model($item, ['url' => $form_url, 'method' => $method, 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$item->getTable()), 'data-callback' => request('callback')]) !!}
{!! Form::hidden('user_id') !!}
@include('partials.alert_box')
<div class="modal-body">
    <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
    <hr>
    <div class="row">
        @if(userIsAdmin())
            <div class="col-12 col-md-4">
                {!! VuexyAdmin::selectTwo('type_id', $types, null, ['data-plugin-options' => '{"placeholder" : "-", "allowClear" : true}', 'id' => 'form-control-type_id-'.$item->getTable(), 'required'], trans('person.data.type_id')) !!}

            </div>
        @else
            {!! Form::hidden('type_id') !!}
        @endif
        <div class="col-12 @if(userIsAdmin()){{ 'col-md-8' }}@endif">
            {!! VuexyAdmin::text('name', null, ['maxlength' => 100, 'required', 'minlength' => 2], trans('person.data.name')) !!}
        </div>
        <div class="col-12 @if(userIsAdmin()){{ 'col-md-6' }}@else{{ 'col-md-6' }}@endif">
            {!! VuexyAdmin::email('email', null, ['maxlength' => 100, 'required' => (request('type_id', $item->type_id) == 'responsible_person'), 'data-persons-email'], trans('person.data.email')) !!}
        </div>
        <div class="col-6 @if(userIsAdmin()){{ 'col-md-6' }}@else{{ 'col-md-6' }}@endif">
            {!! VuexyAdmin::text('phone', null, ['maxlength' => 20, 'required', 'minlength' => 2], trans('person.data.phone')) !!}
        </div>
        @if(userIsAdmin())
            <div class="col-12 col-md-4">
                {!! VuexyAdmin::text('code', null, ['maxlength' => 50], trans('person.data.code')) !!}
            </div>
            <div class="col-12 col-md-4">
                {!! VuexyAdmin::selectTwo('stock_id', $stocks, null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-stock_id-'.$item->getTable()], trans('person.data.stock_id')) !!}
            </div>
            <div class="col-12 col-md-4">
                {!! VuexyAdmin::selectTwo('status', get_codebook_opts('status')->pluck('name', 'code')->toArray(), null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-status-'.$item->getTable(), 'required'], trans('skeleton.data.status')) !!}
            </div>
            <div class="col-12 col-md-4">
                {!! VuexyAdmin::text('printer_type', null, ['maxlength' => 50], "person.data.printer_type") !!}
            </div>
            <div class="col-12 col-md-4">
                {!! VuexyAdmin::text('printer_receipt_url', null, ['maxlength' => 50], "person.data.printer_receipt_url") !!}
            </div>
            <div class="col-12 col-md-4">
                {!! VuexyAdmin::text('printer_access_token', null, ['maxlength' => 50], "person.data.printer_access_token") !!}
            </div>
        @else
            {!! Form::hidden('code', $item->code) !!}
            {!! Form::hidden('code', $item->stock_id) !!}
            {!! Form::hidden('status', 'active') !!}
            {!! Form::hidden('printer_type', $item->printer_type) !!}
            {!! Form::hidden('printer_receipt_url', $item->printer_receipt_url) !!}
            {!! Form::hidden('printer_access_token', $item->printer_access_token) !!}
        @endif
        <div class="col-12">
            {!! VuexyAdmin::textarea('note', null, ['maxlength' => 255], trans('person.data.note')) !!}
        </div>
    </div>
    <div class="row {{ ($item->type_id == 'salesman_person') ? '' : 'hidden' }}" data-kpi-values>
        <div class="col-12">
            <p><strong>KPI naplata</strong></p>
        </div><?php $kpi_values = trans('person.vars.kpi_values'); ?>
        @foreach(trans('person.vars.kpi') as $key => $value)
        <div class="col-6 col-md-3">
            {!! VuexyAdmin::text("kpi_values[{$key}]", is_null($item->kpi_values) ? $kpi_values[$key] : array_get($item->kpi_values, $key, $kpi_values[$key]), ['data-a-dec' => ".", 'data-a-sep' => "", 'required', 'class' => 'form-control autonumeric'], $value) !!}
        </div>
        @endforeach
    </div>
    @if(userIsAdmin())
    <div class="row">
        <div class="col-12 mb-1">
            {!! VuexyAdmin::checkbox('assign_to_user', 1, !is_null($item->user_id), [], trans('person.actions.assign')) !!}
        </div>
        <div class="col-12 mb-1">
            {!! VuexyAdmin::checkbox('invite_user', 1, false, ['disabled' => is_null($item->user_id)], trans('person.actions.invite')) !!}
        </div>
    </div>
    @endif
    @if(userIsAdmin())
        <div class="@if($item->type_id != 'focuser_person'){{ 'hidden' }}@endif" data-focuser-person-tabs>
            <ul class="nav nav-tabs" role="tablist">
                @foreach(trans('person.vars.tabs') as $tab_key => $tab_value)
                    <li class="nav-item">
                        <a class="nav-link @if($active = ($tab_key == 'categories')){{ 'active' }}@endif"
                           id="{{ $tab_key }}-tab" data-toggle="tab" href="#{{ $tab_key }}"
                           aria-controls="{{ $tab_key }}" role="tab"
                           aria-selected="@if($active){{ 'true' }}@else{{ 'false' }}@endif">{{ $tab_value }}</a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content">
                @foreach(trans('person.vars.tabs') as $tab_key => $tab_value)
                    <div class="tab-pane @if($tab_key == 'categories'){{ 'active' }}@endif" id="{{ $tab_key }}"
                         aria-labelledby="{{ $tab_key }}-tab" role="tabpanel">
                        @include('person.form.'.$tab_key)
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
<div class="modal-footer">
    <button class="btn btn-default" type="button" data-dismiss="modal">{{ trans('skeleton.actions.cancel') }}</button>
    <button class="btn btn-success" type="submit">{{ trans('skeleton.actions.submit') }}</button>
</div>
{!! Form::close() !!}

<script>
    $(document).ready(function () {
        App.validate('.{{ $form_class }}', {
            submitHandler: function (form) {
                AjaxForm.init('.{{ $form_class }}');
            }
        });
        select2init($('.{{ $form_class }}'), {
            dropdownParent: $('.{{ $form_class }}').parent(),
        });
        $('.autonumeric').autoNumeric('init', { vMin: 0, mDec: 2 });
        @if(userIsAdmin())
        $('select#form-control-type_id-persons').change(function (e) {
            // Prevent default
            e.preventDefault();
            // Check: Focuser person
            if ($(this).val() === 'focuser_person') {
                $('[data-focuser-person-tabs]').removeClass('hidden');
            } else {
                $('[data-focuser-person-tabs]').addClass('hidden');
            }
            // Check: Responsible person
            var $email = $('input[type="email"][data-persons-email]');
            if ($(this).val() === 'responsible_person') {
                $email.attr('required', 'required').prop('required', true).parent().addClass('required');
            } else {
                $email.removeAttr('required').prop('required', false).parent().removeClass('required');
            }
            // Check: Salesman person
            if ($(this).val() === 'salesman_person') {
                $('[data-kpi-values]').removeClass('hidden');
            } else {
                $('[data-kpi-values]').addClass('hidden');
            }
        });
        clientCategories();
        clientProducts();
        $('input[name="assign_to_user"]').change(function () {
            $('input[name="invite_user"]').prop('disabled', !$(this).is(':checked')).prop('checked', false);
        });
        @endif
    });
    @if(userIsAdmin())
    function clientCategories() {
        $('input[name="select-all-categories"]').change(function () {
            $('input[data-client-category]').prop('checked', $(this).is(':checked'))
        });
    }

    function clientProducts() {
        // Selected products
        var products = {!! json_encode(collect($products_selected)->pluck('id')->transform(function($id) {return intval($id);})->toArray()) !!};
        var product_num = {{ count($products_selected) }};
        // Select2 plugin
        select2ajax($('[data-client-product-search]'), {
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
        $('select[name="products_search"]').change(function (e) {
            // Check
            if ($(this).val() === null) {
                // Return
                return;
            }
            // Data
            var data = $(this).select2('data')[0];
            data.key = product_num;
            // Template
            var template = $('#client-product-template').html();
            Mustache.parse(template);
            $('[data-client-product-list]').prepend(Mustache.render(template, data));
            // Product
            products.push(parseInt(data.id));
            product_num++;
            // Reset
            $(this).val(null).trigger('change');
            // Check
            if (product_num > 0) {
                $('div[data-client-product-no-results]').removeClass('show');
            }
        });
        // Remove
        $('body').on('click', 'a[data-client-product-remove]', function (e) {
            // Prevent default
            e.preventDefault();
            // Product
            var index = products.indexOf(parseInt($(this).data('client-product-remove')));
            if (index > -1) {
                products.splice(index, 1);
            }
            product_num--;
            // Check
            if (product_num <= 0) {
                $('div[data-client-product-no-results]').addClass('show');
                product_num = 0;
            }
            // Remove
            $(this).parent().parent().remove();
        })
    }
    @endif
</script>
