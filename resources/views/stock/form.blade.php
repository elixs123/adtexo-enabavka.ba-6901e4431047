{!! Form::model($item, ['url' => $form_url, 'method' => $method, 'files' => true, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$item->getTable())]) !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
        <hr>
        <div class="row">
            <div class="col-12 col-lg-4">
                {!! VuexyAdmin::text('code', null, ['maxlength' => 50, 'required'], trans('stock.data.code')) !!}
            </div>
            <div class="col-12 col-lg-4">
                {!! VuexyAdmin::text('name', null, ['maxlength' => 100, 'required'], trans('stock.data.name')) !!}
            </div>
            <div class="col-12 col-lg-4">
                {!! VuexyAdmin::text('original_name', null, ['maxlength' => 100, 'required'], trans('stock.data.full_name')) !!}
            </div>		
            <div class="col-12 col-lg-6">
                {!! VuexyAdmin::text('email', null, ['maxlength' => 100, 'required'], trans('stock.data.email')) !!}
            </div>		
            <div class="col-12 col-lg-6">
                {!! VuexyAdmin::text('phone', null, ['maxlength' => 20, 'required'], trans('stock.data.phone')) !!}
            </div>				
            <div class="col-12 col-lg-6">
                {!! VuexyAdmin::text('tax_rate', null, ['maxlength' => 5, 'required'], trans('stock.data.tax_rate')) !!}
            </div>
            <div class="col-12 col-lg-6">
                {!! VuexyAdmin::selectTwo('currency', get_codebook_opts('currency')->pluck('name', 'code')->toArray(), null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-currency-'.$item->getTable()], trans('stock.data.currency')) !!}
            </div>
            <div class="col-12 col-lg-4">
                {!! VuexyAdmin::text('address', null, ['maxlength' => 100, 'required'], trans('stock.data.address')) !!}
            </div>
            <div class="col-12 col-lg-4">
                {!! VuexyAdmin::text('city', null, ['maxlength' => 100, 'required'], trans('stock.data.city')) !!}
            </div>
            <div class="col-12 col-lg-4">
                {!! VuexyAdmin::text('postal_code', null, ['maxlength' => 100, 'required'], trans('stock.data.postal_code')) !!}
            </div>
            <div class="col-12 col-lg-6">
                {!! VuexyAdmin::selectTwo('country_id', get_codebook_opts('countries')->pluck('name', 'code')->toArray(), null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-country_id-'.$item->getTable()], trans('stock.data.country')) !!}
            </div>
            <div class="col-12 col-lg-6">
                {!! VuexyAdmin::selectTwo('status', get_codebook_opts('status')->pluck('name', 'code')->toArray(), null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-status-'.$item->getTable()], trans('skeleton.data.status')) !!}
            </div>
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
    });
</script>
