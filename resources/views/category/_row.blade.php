<tr>
    <td>{{ isset($item->name_length) ? $item->name_length : $item->name }}</td>
    <td><div class="bullet bullet-sm" style="background-color: {{ $item->rStatus->background_color }};" title="{{ $item->rStatus->name }}" data-tooltip></div></td>
    @can('edit-category')
    <td class="td-actions">
        <a data-toggle="modal" data-target="#form-modal1" data-href="{{ route('category.edit', [$item->id]) }}" title="{{ trans('category.actions.edit') }}" data-tooltip><i class="feather icon-edit-1"></i></a>
        <a data-toggle="modal" data-target="#form-modal1" data-href="category/translate/{{ $item->id }}/{{ $item->lang_id }}" title="{{ trans('category.actions.translate') }}" data-tooltip><i class="feather icon-file-text"></i></a>
    </td>
    @endcan
</tr>
