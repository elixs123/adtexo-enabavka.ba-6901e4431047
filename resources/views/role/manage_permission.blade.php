{!! Form::model($role_data, ['url' => $form_url, 'method' => $method, 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$role_data->getTable())]) !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase"><span class="text-primary">{{ $role_data->name }}</span>: {{ $form_title }}</strong></p>
        <hr>
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Model</th>
                    @foreach(trans('role.permission') as $val)
                    <th>{{ $val }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>@php $total = 0; $selected = 0; @endphp
                @foreach($permissions as $modul => $items)
                    @if(!in_array($modul, ['Shop']))
                <tr>
                    <td><strong>{{ $modul }}</strong></td>
                    @foreach(trans('role.permission') as $key => $val)
                    <td>
                        @if(is_null($item = $items->firstWhere('name', $key.'-'.strtolower($modul))))
                        <span>&nbsp;</span>
                            @else
                        {!! VuexyAdmin::checkbox('permission_id[]', $item->id, $checked = $role_data->rPermissions->contains('id', $item->id), ['data-permission'], $item->name) !!}
                        @php $total += 1; $selected += $checked ? 1 : 0; @endphp
                        @endif
                    </td>
                    @endforeach
                </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Other</th>
                    <th>Permission</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $modul => $items)
                    @if(!is_null($item = $items->firstWhere('name', 'delete-'.strtolower($modul))))
                <tr>
                    <td><strong>{{ $modul }}</strong></td>
                    <td>
                        {!! VuexyAdmin::checkbox('permission_id[]', $item->id, $checked = $role_data->rPermissions->contains('id', $item->id), ['data-permission'], $item->name) !!}
                        @php $total += 1; $selected += $checked ? 1 : 0; @endphp
                    </td>
                </tr>
                    @endif
                @endforeach
                <tr>
                    <td><strong>User</strong></td>
                    <td>
                        @if(is_null($item = $permissions['User']->firstWhere('name', 'login-as')))
                        <span>&nbsp;</span>
                        @else
                        {!! VuexyAdmin::checkbox('permission_id[]', $item->id, $checked = $role_data->rPermissions->contains('id', $item->id), ['data-permission'], $item->name) !!}
                        @php $total += 1; $selected += $checked ? 1 : 0; @endphp
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Shop</strong></td>
                    <td>
                        @if(is_null($item = $permissions['Shop']->firstWhere('name', 'view-shop')))
                        <span>&nbsp;</span>
                        @else
                        {!! VuexyAdmin::checkbox('permission_id[]', $item->id, $checked = $role_data->rPermissions->contains('id', $item->id), ['data-permission'], $item->name) !!}
                        @php $total += 1; $selected += $checked ? 1 : 0; @endphp
                        @endif
                    </td>
                </tr>
                @if($total)
                <tr class="thead-light">
                    <td colspan="4" class="text-center">
                        {!! VuexyAdmin::checkbox('select-all', 1, $total == $selected, [], trans('skeleton.select_all')) !!}
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
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
