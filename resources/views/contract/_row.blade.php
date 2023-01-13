<tr id="row{{ $item->uid }}">
    <td><span>{{ $item->rClient->name }}</span></td>
    <td><span class="badge badge-info text-uppercase">{{ $item->total_qty }}</span></td>
    <td><span class="badge badge-danger text-uppercase">{{ $item->total_bought }}</span></td>
    <td><span class="badge badge-success text-uppercase">{{ $item->in_stock }}</span></td>
    <td><div class="bullet bullet-sm" style="background-color: {{ $item->rStatus->background_color }};" title="{{ $item->rStatus->name }}" data-tooltip></div></td>
    @if(can('edit-contract') || can('delete-contract'))
    <td class="td-actions">
        @can('edit-contract')
        <a data-toggle="modal" data-target="#form-modal1" title="{{ trans('contract.actions.products') }}" data-href="{{ route('contract.products', [$item->id]) }}" data-tooltip data-contract-products><i class="feather icon-file-text"></i></a>
        <a data-toggle="modal" data-target="#form-modal1" title="{{ trans('contract.actions.edit') }}" data-href="{{ route('contract.edit', [$item->id]) }}" data-tooltip><i class="feather icon-edit-1"></i></a>
        @endcan
    </td>
    @endif
</tr>
