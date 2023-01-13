{!! Form::model($item, ['url' => $form_url, 'method' => $method, 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$item->getTable()), 'data-callback' => isset($callback) ? $callback : null]) !!}
    {!! Form::hidden('type_id', $type_id) !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
        <hr>
        <div class="row">
            <div class="col-12">
                {!! VuexyAdmin::selectTwoAjax('client_id', [], null, ['data-plugin-options' => '{"placeholder": "'.trans('route.placeholders.client').'", "ajax": {"url": "'.route('client.search', ['t' => $client_type]).'", "type": "get"}}', 'id' => 'form-control-client_id-'.$item->getTable(), 'required'], trans('document.data.client_id')) !!}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal" tabindex="-1">{{ trans('skeleton.actions.cancel') }}</button>
        <a class="btn btn-success disabled" href="{{ route('document.create', ['type_id' => $type_id]) }}" disabled data-client-choosen>{{ trans('document.actions.choose_client') }}</a>
    </div>
{!! Form::close() !!}

<script>
    var back = "{{ request('back', route('shop.index')) }}";
    $(document).ready(function () {
        App.validate('.{{ $form_class }}', {
            submitHandler: function(form) {
                return false;
            }
        });
    
        select2ajax($('.{{ $form_class }}'), {
            dropdownParent: $('.{{ $form_class }}').parent(),
        });
    
        var $a = $('a[data-client-choosen]');
        $('select#form-control-client_id-documents').change(function() {
            var href = $a.attr('href') + '&client_id=' + $(this).val() + '&back=' + back;
    
            $a.attr('href', href).prop('disabled', false).removeClass('disabled');
        });
    
        $a.click(function (e) {
            e.preventDefault();
            loader_on();
            $('#form-modal1').find("[data-form-modal-content]:eq(0)").load($a.attr('href'), function(response, status, xhr) {
                if (status === "error") {
                    notify({
                        type: 'error',
                        message: xhr.statusText,
                    });
                }
                loader_off();
            });
        });
    });
</script>
