<tr id="row{{ $item->uid }}">
    <td>{{ $item->name }}</td>
    <td>{{ $item->label }}</td>
    <td><div class="bullet bullet-sm" style="background-color: {{ $item->rStatus->background_color }};" title="{{ $item->rStatus->name }}" data-tooltip></div></td>
    @can('edit-role')
    <td class="td-actions">
        <a data-toggle="modal" data-target="#form-modal1" data-href="{{ route('role.edit', [$item->id]) }}"  title="{{ trans('role.actions.edit') }}" data-tooltip><i class="feather icon-edit-1"></i></a>
        <a data-toggle="modal" data-target="#form-modal1" data-href="{{ route('role.permission.edit', [$item->id]) }}" title="{{ trans('role.actions.permission') }}" data-tooltip><i class="feather icon-unlock"></i></a>
    </td>
    @endcan
</tr>
