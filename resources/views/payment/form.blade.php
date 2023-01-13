{!! Form::model($item, ['url' => $form_url, 'method' => $method, 'files' => true, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$item->getTable()), 'data-callback' => request('callback')]) !!}
{!! Form::hidden('status', 'not_confirmed') !!}
@include('partials.alert_box')
<div class="modal-body">
    <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
    <hr>
    <div class="row">
        <div class="col-12 col-md-6">
            @if($method == 'post')
            {!! VuexyAdmin::selectTwo('type', trans('payment.vars.type'), 'express_post', ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-type-'.$item->getTable(), 'required'], trans('payment.data.type')) !!}
            @else
            {!! VuexyAdmin::text('type_value', trans('payment.vars.type')[$item->type], ['disabled'], trans('payment.data.type')) !!}
            {!! Form::hidden('type', $item->type) !!}
            @endif
        </div>
        <div class="col-12 col-md-6">
            @if($method == 'post')
            {!! VuexyAdmin::selectTwo('service', trans('payment.vars.services'), 'express_one', ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-service-'.$item->getTable(), 'required'], trans('payment.data.service')) !!}
            @else
            {!! VuexyAdmin::text('service_value', trans('payment.vars.services')[$item->service], ['disabled'], trans('payment.data.service')) !!}
            {!! Form::hidden('service', $item->service) !!}
            @endif
        </div>
        @if($item->status != 'confirmed')
        <div class="col-12">
            {!! VuexyAdmin::file('file', ['path' => config('file.payment.path').'/', 'required' => ($method == 'post')], trans('payment.data.file'), trans('skeleton.allowed_extensions', ['ext' => config('file.payment.extensions')])) !!}
        </div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-default" type="button" data-dismiss="modal">{{ trans('skeleton.actions.cancel') }}</button>
    @if($item->status != 'confirmed')
    <button class="btn btn-success" type="submit">{{ trans('skeleton.actions.submit') }}</button>
    @endif
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
        
    });
</script>
