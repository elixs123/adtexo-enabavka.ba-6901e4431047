<tr id="row{{ $item->uid }}">
    <td>{{ date('d.m.y. H:i', strtotime($item->created_at)) }}</td>
    <td>{{ $item->rStock->name }}</td>
    <td title="{{ $item->note }}" data-tooltip class="{{ $item->qty > 0 ? 'success' : 'danger' }}">{{ $item->qty }}</td>
    <td class="td-actions">
        <a data-toggle="modal" data-target="{{ isset($modal) ? $modal : '#form-modal2' }}" data-href="{{ route('product-stock.edit', [$item->id]) }}" title="{{ trans('skeleton.actions.edit') }}" data-tooltip><i class="feather icon-edit-1"></i></a></span>
        <a class="delete-link" data-id="{{ $item->uid }}" data-action="{{ url('/product-stock/' . $item->id) }}" data-text="{{ trans('skeleton.delete_msg') }}"><i data-tooltip title="{{ trans('skeleton.actions.delete') }}" class="feather icon-trash"></i></a>
    </td>
</tr>
