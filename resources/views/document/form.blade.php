{!! Form::model($item, ['url' => $form_url, 'method' => $method, 'files' => true, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$item->getTable()), 'data-callback' => isset($callback) ? $callback : null]) !!}
    {!! Form::hidden('type_id', $type_id) !!}
    {!! Form::hidden('client_type', $client->type_id) !!}
    {!! Form::hidden('country_id', $client->country_id) !!}
    {!! Form::hidden('status', 'draft') !!}
    {!! Form::hidden('back', request('back', route('shop.index'))) !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
        <hr>
        <div class="row">
            <div class="col-6 col-md-4">
                @if(userIsClient())
                {!! VuexyAdmin::text('date_of_order', now()->toDateString(), ['required', 'readonly'], trans('document.data.date_of_order')) !!}
                @else
                {!! VuexyAdmin::date('date_of_order', now()->toDateString(), ['required'], trans('document.data.date_of_order')) !!}
                @endif
            </div>
            <div class="col-12 col-md-8">
                {!! VuexyAdmin::text('client', $client->name, ['maxlength' => 100, 'required', 'class' => 'form-control', 'readonly'], trans('document.data.client_id')) !!}
                {!! Form::hidden('client_id', $client->id) !!}
            </div>
            @if(in_array($type_id, ['preorder', 'order', 'offer', 'cash']))
                @if(!userIsClient())
            <div class="col-12 {{ in_array($type_id, ['offer', 'cash']) ? 'col-md-3' : (($payment_type == 'advance_payment') ? 'col-md-4' : 'col-md-6') }}" data-document-col>
                {!! VuexyAdmin::selectTwo('payment_type', get_codebook_opts('payment_type')->pluck('name', 'code')->toArray(), is_null($client->payment_type) ? $payment_type : (($type_id == 'offer') ? $payment_type : $client->payment_type), ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-payment_type-'.$item->getTable(), 'required', 'disabled' => $payment_type_lock], trans('document.data.payment_type')) !!}
                    @if($payment_type_lock)
                {!! Form::hidden('payment_type', $payment_type) !!}
                    @endif
            </div>
            <div class="col-12 {{ in_array($type_id, ['offer', 'cash']) ? 'col-md-3' : (($payment_type == 'advance_payment') ? 'col-md-4' : 'col-md-6') }}" data-document-col>
                {!! VuexyAdmin::selectTwo('payment_period', get_codebook_opts('payment_period')->pluck('name', 'code')->toArray(), is_null($client->payment_period) ? $payment_period : (($type_id == 'offer') ? $payment_period : $client->payment_period), ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-payment_period-'.$item->getTable(), 'required', 'disabled' => $payment_period_lock], trans('document.data.payment_period')) !!}
                    @if($payment_period_lock)
                {!! Form::hidden('payment_period', $payment_period) !!}
                    @endif
            </div>
                    @if(in_array($type_id, ['offer', 'cash']))
            <div class="col-6 col-md-3" data-document-col data-document-col-discount data-document-discount1>
                {!! VuexyAdmin::text('payment_discount', format_price($discount1), ['data-plugin-autonumeric', 'data-a-dec' => ",", 'data-a-sep' => ".", 'maxlength' => 10, 'required', 'class' => 'form-control', 'readonly' => (($type_id == 'cash') || true)], trans('document.data.payment_discount')) !!}
            </div>
                    @else
            {!! Form::hidden('payment_discount', format_price($discount1)) !!}
                    @endif
            <div class="col-6 {{ in_array($type_id, ['offer', 'cash']) ? 'col-md-3' : (($payment_type == 'advance_payment') ? 'col-md-4' : 'col-md-6 d-none') }}" data-document-col data-document-col-discount data-document-discount2>
                {!! VuexyAdmin::text('discount_value1', format_price($discount2), ['data-plugin-autonumeric', 'data-a-dec' => ",", 'data-a-sep' => ".", 'maxlength' => 10, 'required', 'class' => 'form-control'], trans('document.data.discount_value1')) !!}
            </div>
                @else
            <div class="col-12">
                {!! Form::hidden('payment_type', $payment_type) !!}
                {!! Form::hidden('payment_period', $payment_period) !!}
                {!! Form::hidden('payment_discount', format_price($discount1)) !!}
                {!! Form::hidden('discount_value1', format_price($discount2))!!}
            </div>
                @endif
            <div class="col-6 col-md-3">
                {!! VuexyAdmin::selectTwo('delivery_type', $delivery_types, 'paid_delivery', ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-delivery_type-'.$item->getTable(), 'required'], trans('document.data.delivery_type')) !!}
            </div>
            <div class="col-6 col-md-3">
                {!! VuexyAdmin::text('delivery_cost', '0,00', ['data-plugin-autonumeric', 'data-a-dec' => ",", 'data-a-sep' => ".", 'maxlength' => 10, 'required', 'class' => 'form-control', 'readonly' => userIsClient()], trans('document.data.delivery_cost')) !!}
            </div>
            <div class="col-6 col-md-3">
                {!! VuexyAdmin::selectTwo('express_post_type', config('express_post.types_per_country.'.$stock->country_id), 'express_one', ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-express_post_type-'.$item->getTable(), 'required'], trans('document.data.express_post_type')) !!}
            </div>
            <div class="col-6 col-md-3">
                {!! VuexyAdmin::date('delivery_date', null, ['startDate' => now()->toDateString(), 'daysOfWeekDisabled' => '0,6'], trans('document.data.delivery_date')) !!}
            </div>
            @else
            <div class="col-12">
                {!! Form::hidden('payment_type', $payment_type) !!}
                {!! Form::hidden('payment_period', $payment_period) !!}
                {!! Form::hidden('payment_discount', format_price($discount1)) !!}
                {!! Form::hidden('discount_value1', format_price($discount2)) !!}
                {!! Form::hidden('delivery_type', 'paid_delivery') !!}
                {!! Form::hidden('delivery_cost', '0,00') !!}
            </div>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal">{{ trans('skeleton.actions.cancel') }}</button>
        <button class="btn btn-success" type="submit">{{ trans('document.actions.create.'.$type_id) }}</button>
    </div>
{!! Form::close() !!}

<script>
    var document_type = '{{ $type_id }}';
    var document_discount1 = '{{ format_price($discount1) }}';
    var document_discount2 = '{{ format_price($discount2) }}';
    $(document).ready(function () {
        App.validate('.{{ $form_class }}', {
            submitHandler: function(form) {
                AjaxForm.init('.{{ $form_class }}');
            }
        });
        select2init($('.{{ $form_class }}'), {
            dropdownParent: $('.{{ $form_class }}').parent(),
        });
        datePickerPlugin($('.{{ $form_class }}'));
        maskPlugin($('.{{ $form_class }}'));
        autoNumericInit($('.{{ $form_class }}'), {});
        @if(in_array($type_id, ['preorder', 'order', 'offer']))
        // Payment type
        $('select#form-control-payment_type-documents').change(function() {
            // Data
            var payment_type = $(this).val();
            var _class = 'col-md-{{ ($type_id == 'offer') ? '3' : '4' }}';
            // Check
            if (payment_type === 'advance_payment') {
                $('[data-document-col]').removeClass('col-md-6').addClass(_class);
                $('[data-document-col-discount]').removeClass('d-none');
                $('select#form-control-payment_period-documents').val('00_days_period').trigger('change');
            } else {
                $('[data-document-col]').removeClass(_class).addClass('col-md-6');
                $('[data-document-discount1]:eq(0)').addClass('d-none').find('input[type="text"]:eq(0)').val(document_discount1);
                $('[data-document-discount2]:eq(0)').addClass('d-none').find('input[type="text"]:eq(0)').val(document_discount2);
            }
        });
        // Delivery type
        var $delivery_cost = $('#form-control-delivery_cost');
        var $express_post_type = $('#form-control-express_post_type-documents');
        $('select#form-control-delivery_type-documents').change(function() {
            // Check
            if (($(this).val() === 'free_delivery') || ($(this).val() === 'personal_takeover')) {
                $delivery_cost.prop('readonly', true).val('0,00');
                $express_post_type.prop('disabled', true);
            } else {
                @if(!userIsClient())
                $delivery_cost.prop('readonly', false).focus();
                @endif
                $express_post_type.prop('disabled', false);
            }
        });
        @endif
    });
</script>
