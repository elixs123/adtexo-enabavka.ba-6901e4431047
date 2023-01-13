{!! Form::model($item, ['url' => $form_url, 'method' => $method, 'files' => true, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$item->getTable()), 'data-callback' => request('callback')]) !!}
    {!! Form::hidden('user_id') !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
        <hr>
        <div class="row">
            <div class="col-12 col-md-4">
                @if($method == 'post')
                {!! VuexyAdmin::selectTwo('type_id', get_codebook_opts('action_types')->pluck('name', 'code')->toArray(), null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-type_id-'.$item->getTable(), 'required'], trans('action.data.type_id')) !!}
                @else
                {!! VuexyAdmin::text('type', $item->rType->name, ['disabled'], trans('action.data.type_id')) !!}
                {!! Form::hidden('type_id', $item->type_id) !!}
                @endif
            </div>
            <div class="col-12 col-md-8">
                {!! VuexyAdmin::text('name', null, ['maxlength' => 190, 'required', 'minlength' => 2], trans('action.data.name')) !!}
            </div>
            <div class="col-6 col-md-3">
                {!! VuexyAdmin::date('started_at', is_null($item->started_at) ? now() : $item->started_at, ['required'], trans('action.data.started_at')) !!}
            </div>
            <div class="col-6 col-md-3">
                {!! VuexyAdmin::date('finished_at', is_null($item->finished_at) ? now()->addDay() : $item->finished_at, ['required'], trans('action.data.finished_at')) !!}
            </div>
            <div class="col-8 col-md-6">
                @if($method == 'post')
                {!! VuexyAdmin::selectTwo('stock_id', $stocks, null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-stock_id-'.$item->getTable(), 'required'], trans('action.data.stock_id')) !!}
                @else
                {!! VuexyAdmin::text('stock_name', $item->rStock->name, ['disabled'], trans('action.data.stock_id')) !!}
                {!! Form::hidden('stock_id', $item->stock_id) !!}
                @endif
            </div>
            <div class="col-12">
                {!! VuexyAdmin::selectTwo('roles[]', $roles, $action_roles, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-roles-'.$item->getTable(), 'required', 'multiple'], trans('action.data.roles')) !!}
            </div>
            <div class="col-6 col-md-4">
                @if($method == 'post')
                {!! VuexyAdmin::selectTwo('stock_type', trans('action.vars.stock_types'), null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-stock_type-'.$item->getTable(), 'required'], trans('action.data.stock_type')) !!}
                @else
                {!! VuexyAdmin::text('stock_type_value', trans('action.vars.stock_types')[$item->stock_type], ['disabled'], trans('action.data.stock_type')) !!}
                {!! Form::hidden('stock_type', $item->stock_type) !!}
                @endif
            </div>
            <div class="col-6 col-md-4">
                {!! VuexyAdmin::text('qty', 0, ['maxlength' => 10, 'required'], trans('action.data.stock')) !!}
            </div>
            <div class="col-12 col-md-4">
                {!! VuexyAdmin::selectTwo('status', get_codebook_opts('status')->pluck('name', 'code')->toArray(), null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-status-'.$item->getTable(), 'required'], trans('skeleton.data.status')) !!}
            </div>
            <div class="col-12 col-md-4">
                {!! VuexyAdmin::file('photo', ['path' => config('picture.action_path').'/medium_'], trans('action.data.photo'), trans('skeleton.allowed_extensions', ['ext' => 'JPG'])) !!}
            </div>
            <div class="col-12 col-md-4">
                {!! VuexyAdmin::file('presentation', ['path' => config('file.action.path').'/'], trans('action.data.presentation'), trans('skeleton.allowed_extensions', ['ext' => config('file.action.extensions')])) !!}
            </div>
            <div class="col-12 col-md-4">
                {!! VuexyAdmin::file('technical_sheet', ['path' => config('file.action.path').'/'], trans('action.data.technical_sheet'), trans('skeleton.allowed_extensions', ['ext' => config('file.action.extensions')])) !!}
            </div>
            <div class="col-12">
                <div class="form-group">
                    {!! VuexyAdmin::checkbox('free_delivery', 1, $item->free_delivery, [], trans('action.data.free_delivery')) !!}
                </div>
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
        datePickerPlugin($('.{{ $form_class }}'));
        select2init($('.{{ $form_class }}'), {
            dropdownParent: $('.{{ $form_class }}').parent(),
        });
        
        {{--var method = '{{ $method }}', $stock = $('input#form-control-qty');--}}
        {{--$('select#form-control-stock_type-actions').change(function() {--}}
        {{--    if ($(this).val() === 'unlimited') {--}}
        {{--        $stock.attr('readonly', 'readonly').prop('readonly', true);--}}
        {{--        if (method === 'post') {--}}
        {{--            $stock.val(0);--}}
        {{--        }--}}
        {{--    } else {--}}
        {{--        $stock.removeAttr('readonly').prop('readonly', false);--}}
        {{--    }--}}
        {{--})--}}
    });
</script>
