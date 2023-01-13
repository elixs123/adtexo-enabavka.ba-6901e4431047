{!! Form::model($item, ['url' => $form_url, 'method' => $method, 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$item->getTable())]) !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
        <hr>
        <div class="row">
            <div class="col-6">
                {!! VuexyAdmin::selectTwo('lang_id', config('app.locales'), null, ['required', 'data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-lang_id-'.$item->getTable()], trans('skeleton.lang')) !!}
            </div>
            <div class="col-6">
                    {!! VuexyAdmin::selectTwo('father_id', $categories->pluck('name_length', 'id')->prepend('-', 0)->toArray(), null, ['required', 'data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-father_id-'.$item->getTable()], trans('category.data.parent')) !!}
            </div>
            <div class="col-12">
                {!! VuexyAdmin::text('name', null, ['maxlength' => 100, 'required'], trans('category.data.name')) !!}
            </div>
            <div class="col-12">
                {!! VuexyAdmin::text('description', null, ['maxlength' => 255], trans('category.data.description')) !!}
            </div>
            <div class="col-6">
                {!! VuexyAdmin::number('priority', 1, ['maxlength' => 5, 'required'], trans('category.data.priority')) !!}
            </div>
            <div class="col-6">
                {!! VuexyAdmin::selectTwo('status', get_codebook_opts('status')->pluck('name', 'code')->toArray(), null, ['required', 'data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-status-'.$item->getTable()], trans('skeleton.data.status')) !!}
            </div>
			<div class="col-12">
				{!! VuexyAdmin::file('photo', ['path' => config('picture.category_path').'/medium_'], trans('product.data.photo'), trans('skeleton.allowed_extensions', ['ext' => 'JPG'])) !!}
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
