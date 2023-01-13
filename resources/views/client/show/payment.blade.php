<div class="row">
    <div class="col-12 col-md-4">
        {!! VuexyAdmin::locked('payment_therms', $item->payment_therms, $item->getPaymentTherms($item->payment_therms), [], trans('client.data.payment_therms')) !!}
    </div>
    <div class="col-12 col-md-4">
        {!! VuexyAdmin::locked('payment_period', $item->payment_period, $item->getPaymentPeriods($item->payment_period), [], trans('client.data.payment_period')) !!}
    </div>
    <div class="col-12 col-md-4">
        {!! VuexyAdmin::locked('payment_type', $item->payment_type, $item->getPaymentTypes($item->payment_type), [], trans('client.data.payment_type')) !!}
    </div>
    <div class="col-6 col-md-4">
        {!! VuexyAdmin::locked('payment_discount', $item->payment_discount, $item->payment_discount, [], trans('client.data.payment_discount')) !!}
    </div>
    <div class="col-6 col-md-4">
        {!! VuexyAdmin::locked('discount_value1', $item->discount_value1, $item->discount_value1, [], trans('client.data.discount_value1')) !!}
    </div>
    <div class="col-6 col-md-4">
        {!! VuexyAdmin::locked('discount_value2', $item->discount_value2, $item->discount_value2, [], trans('client.data.discount_value2')) !!}
    </div>
    <div class="col-12">
        {!! VuexyAdmin::locked('note', $item->note, nl2br($item->note), [], trans('client.data.note')) !!}
    </div>
</div>
