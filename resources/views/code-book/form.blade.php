{!! Form::model($item, ['url' => $form_url, 'method' => $method, 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$item->getTable())]) !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
        <hr>
        <div class="row">
            <div class="col-12">
                {!! VuexyAdmin::selectTwo('type', trans('codebook.vars.types'), null, ['data-plugin-options' => '{}', 'required'], trans('codebook.data.type')) !!}
            </div>
            <div class="col-12 col-md-6">
                {!! VuexyAdmin::text('name', null, ['maxlength' => 200, 'required', 'minlength' => 2], trans('codebook.data.name')) !!}
            </div>
            <div class="col-12 col-md-6">
                {!! VuexyAdmin::text('code', null, ['maxlength' => 100, 'required', 'minlength' => 2], trans('codebook.data.code')) !!}
            </div>
            <div class="col-12 mb-1">
                {!! VuexyAdmin::checkbox('with_colors', 1, !(is_null($item->background_color) && is_null($item->color)), [], 'Boje?') !!}
            </div>
            <div class="col-12 col-md-6">
                {!! VuexyAdmin::color('background_color', trans('codebook.vars.colors.background_color'), ['maxlength' => 8, 'required' => !is_null($item->background_color), 'minlength' => 7, 'disabled' => is_null($item->background_color)], trans('codebook.data.background_color')) !!}
            </div>
            <div class="col-12 col-md-6">
                {!! VuexyAdmin::color('color', trans('codebook.vars.colors.color'), ['maxlength' => 8, 'required' => !is_null($item->color), 'minlength' => 7, 'disabled' => is_null($item->color)], trans('codebook.data.color')) !!}
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
        // Colors
        $('input[name="with_colors"]').change(function () {
            var checked = $(this).is(':checked');
            $('#form-control-background_color').prop('disabled', !checked).prop('required', checked).parent().toggleClass('required');
            $('#form-control-color').prop('disabled', !checked).prop('required', checked).parent().toggleClass('required');
        })
    });
</script>
