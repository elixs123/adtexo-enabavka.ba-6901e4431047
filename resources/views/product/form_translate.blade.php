{!! Form::model($item, ['url' => $form_url, 'method' => $method, 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$item->getTable())]) !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
        <hr>
        <div class="row">
            <div class="col-12">
                    {!! VuexyAdmin::selectTwo('translation[lang_id]', config('app.locales'), null, ['required', 'data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-status-'.$item->getTable()], trans('skeleton.lang')) !!}
            </div>                      
            <div class="col-12">
                {!! VuexyAdmin::text('translation[name]', $item->name, ['maxlength' => 100, 'required'], trans('product.data.name')) !!}
            </div>
            <div class="col-12">
                {!! VuexyAdmin::textarea('translation[text]', $item->text, ['maxlength' => 5000], trans('product.data.text')) !!}
            </div>  
        <input type="hidden" value="{{ $item->product_id }}" name="translation[product_id]">                     
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