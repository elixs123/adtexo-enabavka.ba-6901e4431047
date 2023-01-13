<tr id="row{{ $item->uid }}">
    <td>{{ $item->code }}</td>
    <td>{{ $item->name }}</td>
    <td>{{ $item->city }}</td>
    <td>{{ $item->rCountry->name }}</td>
    <td><div class="bullet bullet-sm" style="background-color: {{ $item->rStatus->background_color }};" title="{{ $item->rStatus->name }}" data-tooltip></div></td>
    @can('edit-stock')
    <td class="td-actions">
        <a data-toggle="modal" data-target="#form-modal1" data-href="{{ route('stock.edit', [$item->id]) }}" title="{{ trans('stock.actions.edit') }}" data-tooltip><i class="feather icon-edit-1"></i></a>
    </td>
    @endcan
</tr>
