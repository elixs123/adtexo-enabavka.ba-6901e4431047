<div class="row">
    <div class="col-12">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>{{ trans('client.data.categories') }}</th>
                    <th>{{ trans('client.actions.assign') }}</th>
                </tr>
            </thead>
            <tbody>@php $total = 0; $selected = 0; @endphp
                @foreach($categories as $key => $val)
                <tr>
                    <td><strong>{{ $val }}</strong></td>
                    <td>
                        {!! VuexyAdmin::checkbox('categories[]', $key, $checked = in_array($key, is_null($categories_selected) ? [] : $categories_selected), ['data-client-category'], '&nbsp;') !!}
                        @php $total += 1; $selected += $checked ? 1 : 0; @endphp
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        {!! VuexyAdmin::checkbox('select-all-categories', 1, $total == $selected, [], trans('skeleton.select_all')) !!}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
