{!! Form::model($item, ['url' => $form_url, 'method' => $method, 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$item->getTable()), 'data-callback' => isset($callback) ? $callback : null]) !!}
    @include('partials.alert_box')
    {!! Form::hidden('client_id', $item->id) !!}
    {!! Form::hidden('parent_id', $item->parent_id) !!}
    @if(userIsSalesman() || userIsFocuser() || !is_null($parent_id))
    {!! Form::hidden('payment_therms', array_get($parent, 'payment_therms', 'payment')) !!}
    {!! Form::hidden('payment_period', array_get($parent, 'payment_period', userIsFocuser() ? '00_days_period' : '30_days_period')) !!}
    {!! Form::hidden('payment_type', array_get($parent, 'payment_type', userIsFocuser() ? 'cash_payment' :  'wire_transfer_payment')) !!}
        @if(!userIsSalesman())
    {!! Form::hidden('payment_discount', array_get($parent, 'payment_discount', '0.0')) !!}
        @endif
    {!! Form::hidden('discount_value1', array_get($parent, 'discount_value1', '0.0')) !!}
    {!! Form::hidden('discount_value2', array_get($parent, 'discount_value1', '0.0')) !!}
    @endif
    @if(userIsSalesman() || userIsFocuser())
    {!! Form::hidden('stock_id', auth()->user()->rPerson->stock_id) !!}
    {!! Form::hidden('lang_id', app()->getLocale()) !!}
    {!! Form::hidden('status', 'pending') !!}
    @endif
    @if(userIsFocuser())
    {!! Form::hidden('salesman_person_id', auth()->user()->rPerson->id) !!}
    @endif
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
        <hr>
        <ul class="nav nav-tabs" role="tablist">
            @foreach($item->getClientTabs($parent_id) as $tab_key => $tab_value)
            <li class="nav-item">
                <a class="nav-link @if($active = ($tab_key == 'main')){{ 'active' }}@endif @if(($tab_key == 'routes') && (is_null($parent_id) && !$item->is_location)){{ 'hidden' }}@endif" id="{{ $tab_key }}-tab" data-toggle="tab" href="#{{ $tab_key }}" aria-controls="{{ $tab_key }}" role="tab" aria-selected="@if($active){{ 'true' }}@else{{ 'false' }}@endif">{{ $tab_value }}</a>
            </li>
            @endforeach
        </ul>
        <div class="tab-content">
            @foreach($item->getClientTabs($parent_id) as $tab_key => $tab_value)
            <div class="tab-pane @if($tab_key == 'main'){{ 'active' }}@endif" id="{{ $tab_key }}" aria-labelledby="{{ $tab_key }}-tab" role="tabpanel">
                @include('client.form.'.$tab_key)
            </div>
            @endforeach
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal">{{ trans('skeleton.actions.cancel') }}</button>
        <button class="btn btn-success" type="submit">{{ trans('skeleton.actions.submit') }}</button>
    </div>
{!! Form::close() !!}

<script>
    $(document).ready(function () {
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
        });
        maskPlugin($('.{{ $form_class }}'));
        App.tooltip();
        @if(is_null($parent_id))
        clientMain();
        @endif
        clientAddress();
        @if((($method == 'post') && can('create-route')) || (($method == 'put') && can('edit-route')))
        clientRoutes();
        @endif
        clientCategories();
        clientProducts();
        clientActions();
    });
    @if(is_null($parent_id))
    function clientMain() {
        $('select[name="type_id"]').change(function() {
            var required = ($(this).val() === 'private_client');
            fieldIsRequired('jib', $('input[name="jib"]'), required);
            fieldIsRequired('pib', $('input[name="pib"]'), required);
            fieldIsRequired('code', $('input[name="code"]'), required);
            fieldIsRequired('location_type_id', $('select[name="location_type_id"]'), required);
            fieldIsRequired('category_id', $('select[name="category_id"]'), required);
            fieldIsRequired('payment_person_id', $('select[name="payment_person_id"]'), required);
            
            if ($(this).val() === 'private_client') {
                $('input[name="photo"]').removeAttr('required').prop('required', false).parents('.form-group').removeClass('required');
            } else {
                $('input[name="photo"]').attr('required', 'required').prop('required', true).parents('.form-group').addClass('required');
            }
        });
    
        $('input[data-is-location]').change(function() {
            var $lc = $('div[data-location-content]'), no_other_locations = $('input[name="no_other_locations"]').is(':checked');
            if ($(this).is(':checked')) {
                $('input[name="location_code"]').val($('input[name="code"]').val());
                $('input[name="location_name"]').val($('input[name="name"]').val());
                $('[data-toggle="tab"][href="#routes"]').removeClass('hidden');
                $('[data-toggle="tab"][href="#categories"], [data-toggle="tab"][href="#products"], [data-toggle="tab"][href="#actions"]').addClass('hidden');
                $('input[name="no_other_locations"]').prop('disabled', false);
                $lc.removeClass('hidden');
            } else {
                $('input[name="location_code"], input[name="location_name"]').val('');
                $('[data-toggle="tab"][href="#routes"]').addClass('hidden');
                $('[data-toggle="tab"][href="#categories"], [data-toggle="tab"][href="#products"], [data-toggle="tab"][href="#actions"]').removeClass('hidden');
                $('input[name="no_other_locations"]').prop('checked', false).prop('disabled', true).trigger('change');
                $lc.addClass('hidden');
            }
        });
        $('input[name="no_other_locations"]').change(function () {
            if ($(this).is(':checked')) {
                $('[data-toggle="tab"][href="#categories"], [data-toggle="tab"][href="#products"], [data-toggle="tab"][href="#actions"]').removeClass('hidden');
            } else {
                if ($('input[data-is-location]').is(':checked')) {
                    $('[data-toggle="tab"][href="#categories"], [data-toggle="tab"][href="#products"], [data-toggle="tab"][href="#actions"]').addClass('hidden');
                }
            }
        });
    }
    function fieldIsRequired(name, $input, required) {
        if ((name === 'jib') || (name === 'location_type_id') || (name === 'category_id') || (name === 'payment_person_id')) {
            $input.attr('required', !required);
            $input.parent().toggleClass('required');
        }
        $input.attr('readonly', required).val('').trigger('change');
    }
    @endif
    function clientAddress() {
        $('button[data-map-location-reset]').click(function(e) {
            e.preventDefault();
            $('input[name="latitude"], input[name="longitude"]').val('');
        });
    }
    function setClientMapLocation(currentLocation) {
        $('input[name="latitude"]').val(currentLocation.latitude.toFixed(6));
        $('input[name="longitude"]').val(currentLocation.longitude.toFixed(6));
    }
    @can('create-person')
    function clientPersonAssign(response) {
        // Option
        var opt = new Option(response.data.name, response.data.id, false, false);
        // Select
        var sid = (response.data.type_id === 'sales_agent_person') ? 'responsible_person' : response.data.type_id;
        $('#form-control-' + sid + '_id-clients').html('').val(null).append(opt).trigger('change');
    }
    @endcan
    @if((($method == 'post') && can('create-route')) || (($method == 'put') && can('edit-route')))
    function clientRoutes() {
        // Parameters
        var $holder = $('[data-routes-holder]');
        var $loader = $('[data-routes-loader]');
        // Change: Select
        $('select[name="salesman_person_id"]').change(function(e) {
            if ($(this).val() === null) {
                // Reset
                $holder.text('');
                // Loader
                $loader.addClass('hidden');
            } else {
                // Loader
                $loader.removeClass('hidden');
                // Ajax
                $.ajax({
                    method: 'get',
                    url: $(this).data('route-url'),
                    data: {
                        'person_id': $(this).val(),
                        'client_id': $('input[name="client_id"]').val()
                    },
                    dataType : 'html',
                    success: function (response) {
                        // Loader
                        $loader.addClass('hidden');
                        // Holder
                        $holder.html(response);
                    },
                    error: function (response) {
                        // Loader
                        $loader.addClass('hidden');
                        // Holder
                        $holder.html(response);
                    }
                });
            }
        });
    }
    @endif
    function clientCategories() {
        $('input[name="select-all-categories"]').change(function() {
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
        // Change: Select City
        $('select[name="city"]').change(function(e) {
			var data = $(this).select2('data')[0];
			$('#form-control-postal_code').val(data.postal_code);
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
    function clientActions() {
        // Selected actions
        var actions = {!! json_encode(collect($actions_selected)->pluck('id')->transform(function($id) {return intval($id);})->toArray()) !!};
        var action_num = {{ count($actions_selected) }};
        // Select2 plugin
        select2ajax($('[data-client-action-search]'), {
            dropdownParent: $('.{{ $form_class }}').parent(),
            ajax: {
                data: function (params) {
                    return {
                        q: params.term,
                        e: actions.join('.')
                    };
                },
                cache: false
            }
        });
        // Change: Select
        $('select[name="actions_search"]').change(function(e) {
            // Check
            if ($(this).val() === null) {
                // Return
                return;
            }
            // Data
            var data = $(this).select2('data')[0];
            data.key = action_num;
            // Template
            var template = $('#client-action-template').html();
            Mustache.parse(template);
            $('[data-client-action-list]').prepend(Mustache.render(template, data));
            // Product
            actions.push(parseInt(data.id));
            action_num++;
            // Reset
            $(this).val(null).trigger('change');
            // Check
            if (action_num > 0) {
                $('div[data-client-action-no-results]').removeClass('show');
            }
        });
        // Remove
        $('body').on('click', 'a[data-client-action-remove]', function (e) {
            // Prevent default
            e.preventDefault();
            // Product
            var index = actions.indexOf(parseInt($(this).data('client-action-remove')));
            if (index > -1) {
                actions.splice(index, 1);
            }
            action_num--;
            // Check
            if (action_num <= 0) {
                $('div[data-client-action-no-results]').addClass('show');
                action_num = 0;
            }
            // Remove
            $(this).parent().parent().remove();
        })
    }
</script>
