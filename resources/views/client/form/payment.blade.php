<div class="row">
    @if(userIsSalesman())
    <div class="col-12">
        {!! VuexyAdmin::text('payment_discount', '0.0', ['maxlength' => 5, 'data-plugin-mask1', 'data-plugin-options' => '{"mask": "##0.0", "placeholder": "0.0", "reverse": true, "selectOnFocus": true}', 'required'], trans('client.data.payment_discount')) !!}
    </div>
    @else
    <div class="col-12 col-md-4">
        {!! VuexyAdmin::selectTwo('payment_therms', $item->getPaymentTherms(), 'payment', ['data-plugin-options' => '{"minimumResultsForSearch": -1, "placeholder" : "-"}', 'id' => 'form-control-payment_therms-'.$item->getTable(), 'required'], trans('client.data.payment_therms')) !!}
    </div>
    <div class="col-12 col-md-4">
        {!! VuexyAdmin::selectTwo('payment_period', $item->getPaymentPeriods(), '30_days_period', ['data-plugin-options' => '{"minimumResultsForSearch": -1, "placeholder" : "-"}', 'id' => 'form-control-payment_period-'.$item->getTable(), 'required'], trans('client.data.payment_period')) !!}
    </div>
    <div class="col-12 col-md-4">
        {!! VuexyAdmin::selectTwo('payment_type', $item->getPaymentTypes(), 'wire_transfer_payment', ['data-plugin-options' => '{"minimumResultsForSearch": -1, "placeholder" : "-"}', 'id' => 'form-control-payment_type-'.$item->getTable(), 'required'], trans('client.data.payment_type')) !!}
    </div>
    <div class="col-6 col-md-4">
        {!! VuexyAdmin::text('payment_discount', '0.0', ['maxlength' => 5, 'data-plugin-mask1', 'data-plugin-options' => '{"mask": "##0.0", "placeholder": "0.0", "reverse": true, "selectOnFocus": true}', 'required'], trans('client.data.payment_discount')) !!}
    </div>
    <div class="col-6 col-md-4">
        {!! VuexyAdmin::text('discount_value1', '0.0', ['maxlength' => 5, 'data-plugin-mask1', 'data-plugin-options' => '{"mask": "##0.0", "placeholder": "0.0", "reverse": true, "selectOnFocus": true}', 'required'], trans('client.data.discount_value1')) !!}
    </div>
    <div class="col-6 col-md-4">
        {!! VuexyAdmin::text('discount_value2', '0.0', ['maxlength' => 5, 'data-plugin-mask1', 'data-plugin-options' => '{"mask": "##0.0", "placeholder": "0.0", "reverse": true, "selectOnFocus": true}', 'required'], trans('client.data.discount_value2')) !!}
    </div>
    <div class="col-6">
        {!! VuexyAdmin::text('allowed_limit_in', '0.0', ['maxlength' => 5, 'data-plugin-mask1', 'data-plugin-options' => '{"mask": "##0.0", "placeholder": "0.0", "reverse": true, "selectOnFocus": true}', 'required'], trans('client.data.allowed_limit_in')) !!}
    </div>
    <div class="col-6">
        {!! VuexyAdmin::text('allowed_limit_outside', '0.0', ['maxlength' => 5, 'data-plugin-mask1', 'data-plugin-options' => '{"mask": "##0.0", "placeholder": "0.0", "reverse": true, "selectOnFocus": true}', 'required'], trans('client.data.allowed_limit_outside')) !!}
    </div>
    @endif
    <div class="col-12">
        {!! VuexyAdmin::textarea('note', null, ['maxlength' => 255], trans('client.data.note')) !!}
    </div>
</div>
