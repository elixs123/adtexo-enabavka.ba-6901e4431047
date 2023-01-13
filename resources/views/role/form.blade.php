{!! Form::model($item, ['url' => $form_url, 'method' => $method, 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$item->getTable())]) !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
        <hr>
        <div class="row">
            <div class="col-12">
                {!! VuexyAdmin::text('name', null, ['maxlength' => 100, 'required', 'minlength' => 2], trans('role.data.name')) !!}
            </div>
            <div class="col-12 col-md-6">
                {!! VuexyAdmin::text('label', null, ['maxlength' => 100, 'required', 'minlength' => 2], trans('role.data.label')) !!}
            </div>
            <div class="col-12 col-md-6">
                {!! VuexyAdmin::selectTwo('status', get_codebook_opts('status')->pluck('name', 'code')->toArray(), null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-status-'.$item->getTable(), 'required'], trans('skeleton.data.status')) !!}
            </div>
            <div class="col-12">
                {!! VuexyAdmin::textarea('description', null, ['maxlength' => 255], trans('role.data.description')) !!}
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
