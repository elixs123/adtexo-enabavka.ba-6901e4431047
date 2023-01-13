{!! Form::model($document, ['url' => route('document.shipping.update', ['id' => $document->id]), 'method' => 'put', 'files' => false, 'autocomplete' => 'false', 'class' =>
($form_class = 'ajax-form-document-shipping-data'), 'data-callback' => 'documentShippingDataChanged']) !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
        <hr>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                {!! VuexyAdmin::text('shipping_data[name]', null, ['maxlength' => 100, 'required', 'class' => 'form-control required'], 'Ime i prezime') !!}
                {!! Form::hidden('shipping_data[email]', null) !!}
                {!! Form::hidden('shipping_data[phone]', null) !!}
            </div>
            <div class="col-md-6 col-sm-12">
                {!! VuexyAdmin::text('shipping_data[address]', null, ['maxlength' => 100, 'required', 'class' => 'form-control required'], 'Adresa') !!}
            </div>
            <div class="col-md-6 col-sm-12">
                {!! Form::hidden('shipping_data[city]', null, ['id' => 'form-control-city']) !!}
                {!! VuexyAdmin::selectTwo('shipping_data[city_id]', $cities, $document->shipping_data['postal_code'], ['id' => 'form-control-city_id', 'required'], trans('client.data.city')) !!}
                {!! Form::hidden('shipping_data[country]', null) !!}
            </div>
            <div class="col-md-6 col-sm-12">
                {!! VuexyAdmin::text('shipping_data[postal_code]', null, ['readonly', 'maxlength' => 20, 'required', 'class' => 'form-control required', 'id' => 'form-control-postal_code'], 'Poštanski broj') !!}
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
    
        $('select#form-control-city_id').change(function(e) {
            $('#form-control-city').val($("#form-control-city_id option:selected").text().slice(0,-8));
            $('#form-control-postal_code').val($(this).val());
        });
    });
</script>
