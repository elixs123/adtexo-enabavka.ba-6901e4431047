{!! Form::model($item, ['url' => $form_url, 'method' => $method, 'files' => true, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$item->getTable())]) !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
        <hr>
        <div class="row">           
            <div class="col-12">
                {!! VuexyAdmin::selectTwo('type_id', get_codebook_opts('document_type')->pluck('name', 'code')->toArray(), null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-type-'.$item->getTable()], trans('skeleton.document.type')) !!}
            </div>
            <div class="col-12">
                {!! VuexyAdmin::selectTwoAjax('client_id', [], null, ['data-plugin-options' => '{"placeholder": "'.trans('route.placeholders.client').'", "ajax": {"url": "'.route('client.search').'", "type": "get"}}', 'id' => 'form-control-client_id-'.$item->getTable()], null) !!}
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
        select2ajax($('.{{ $form_class }}'), {
            dropdownParent: $('.{{ $form_class }}').parent(),
        });        
    });
</script>