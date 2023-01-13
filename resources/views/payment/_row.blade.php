<tr id="row{{ $item->uid }}">
    <td>{{ $item->created_at->format('d.m.Y') }}</td>
    <td>{{ $item->uploaded_at->format('d.m.Y \u H:i') }}<br>{{ $item->rUploadedBy->email }}</td>
    <td>
        @if(is_null($item->confirmed_at))
        -
        @else
        {{ $item->confirmed_at->format(trans('datetime.format.full')) }}<br>{{ $item->rConfirmedBy->email }}
        @endif
    </td>
    <td>
        <span class="badge badge-primary text-uppercase">{{ trans('payment.vars.type')[$item->type] }}</span>
        <span class="badge badge-info text-uppercase">{{ trans('payment.vars.services')[$item->service] }}</span>
    </td>
    <td class="text-right"><strong>{{ format_price($item->total_payments, 2) }} {{ $item->config['currency'] }}</strong></td>
    <td class="text-right"><strong>{{ format_price($item->total_documents, 2) }} {{ $item->config['currency'] }}</strong></td>
    <td><span class="badge text-uppercase" style="background-color: {{ $item->rStatus->background_color }};">{{ $item->rStatus->name }}</span></td>
    <td class="{{ ($item->total_payments - $item->total_documents) == 0 ? 'bg-success' : 'bg-danger' }}">&nbsp;</td>
    @if(can('edit-payment'))
    <td class="td-actions">
        @can('view-payment')
        <a href="{{ route('payment.show', [$item->id]) }}" title="{{ trans('payment.actions.show') }}" data-tooltip><i class="feather icon-external-link"></i></a>
        @endcan
        @can('edit-payment')
        <a data-toggle="modal" data-target="#form-modal1" title="{{ trans('payment.actions.edit') }}" data-href="{{ route('payment.edit', [$item->id]) }}" data-tooltip><i class="feather icon-edit-1"></i></a>
        @endcan
    </td>
    @endif
</tr>
