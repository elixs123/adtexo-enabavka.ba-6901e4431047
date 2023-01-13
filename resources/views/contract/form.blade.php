{!! Form::model($item, ['url' => $form_url, 'method' => $method, 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$item->getTable()), 'data-callback' => request('callback')]) !!}
    {!! Form::hidden('user_id') !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
        <hr>
        <div class="row">
            <div class="col-12">
                @if($method == 'post')
                {!! VuexyAdmin::selectTwoAjax('client_id', $clients, null, ['data-plugin-options' => '{"placeholder": "'.trans('route.placeholders.client').'", "ajax": {"url": "'.route('client.search').'", "type": "get"}}', 'id' => 'form-control-client_id-'.$item->getTable(), 'required'], trans('contract.data.client_id')) !!}
                @else
                {!! VuexyAdmin::text('client', $clients[$item->client_id], ['maxlength' => 100, 'required', 'class' => 'form-control', 'readonly'], trans('contract.data.client_id')) !!}
                {!! Form::hidden('client_id', $item->client_id) !!}
                @endif
            </div>
            <div class="col-12">
                {!! VuexyAdmin::textarea('note', null, ['maxlength' => 255], trans('contract.data.note')) !!}
            </div>
            <div class="col-12">
                {!! VuexyAdmin::selectTwo('status', get_codebook_opts('status')->pluck('name', 'code')->toArray(), null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-status-'.$item->getTable(), 'required'], trans('skeleton.data.status')) !!}
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
