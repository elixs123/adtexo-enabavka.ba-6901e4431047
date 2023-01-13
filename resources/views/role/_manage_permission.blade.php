{!! Form::model($role_data, ['url' => $form_url, 'method' => $method, 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$role_data->getTable())]) !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase"><span class="text-primary">{{ $role_data->name }}</span>: {{ $form_title }}</strong></p>
        <hr>
        <div class="row">@php $total = 0; $selected = 0; @endphp
            @foreach($permissions as $modul => $items)
            <div class="col-6 col-md-4 mb-1">
                <h5>{{ $modul }}</h5>
                @foreach($items->sortBy('name') as $id => $item)
                    {!! VuexyAdmin::checkbox('permission_id[]', $item->id, $checked = $role_data->rPermissions->contains('id', $item->id), ['data-permission'], $item->name) !!}
                    @php $total += 1; $selected += $checked ? 1 : 0; @endphp
                @endforeach
            </div>
            @endforeach
            <div class="col-12 mb-1">
                <hr class="mt-0">
                {!! VuexyAdmin::checkbox('select-all', 1, $total == $selected, [], trans('skeleton.select_all')) !!}
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
        $('input[name="select-all"]').change(function() {
            $('input[data-permission]').prop('checked', $(this).is(':checked'))
        });
    });
</script>
