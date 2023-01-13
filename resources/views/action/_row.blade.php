<tr id="row{{ $item->uid }}">
    <td>{{ $item->name }}</td>
    <td><span class="badge badge-info text-uppercase">{{ $item->rType->name }}</span></td>
    <td>{{ trans('action.vars.stock_types')[$item->stock_type] }} <span class="badge badge-dark" data-toggle="tooltip" title="{{ trans('action.data.stock') }}">{{ $item->qty }}</span> <span class="badge badge-success" data-toggle="tooltip" title="{{ trans('action.data.available') }}">{{ $item->qty - $item->bought - $item->reserved }}</span></td>
    <td>{{ $item->started_at->format('d.m.Y') }}</td>
    <td>{{ $item->finished_at->format('d.m.Y') }}</td>
    <td><div class="bullet bullet-sm" style="background-color: {{ $item->rStatus->background_color }};" title="{{ $item->rStatus->name }}" data-tooltip></div></td>
    @if(can('edit-action'))
    <td class="td-actions">
        @can('edit-action')
        <a data-toggle="modal" data-target="#form-modal1" title="{{ trans('action.actions.products') }}" data-href="{{ route('action.products', [$item->id]) }}" data-tooltip data-action-products><i class="feather icon-file-text"></i></a>
        <a data-toggle="modal" data-target="#form-modal1" title="{{ trans('action.actions.edit') }}" data-href="{{ route('action.edit', [$item->id]) }}" data-tooltip><i class="feather icon-edit-1"></i></a>
        @endcan
    </td>
    @endif
</tr>
