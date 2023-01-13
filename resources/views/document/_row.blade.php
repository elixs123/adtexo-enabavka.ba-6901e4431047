<tr id="row{{ $item->uid }}" class="{{ userIsClient() ? (($item->created_by == auth()->id()) ? 'created-by-me' : 'created-by-salesman') : '' }}">
    @if(!userIsClient())
    <td>
        @if(in_array($item->type_id, ['order']) && in_array($item->status, ['for_invoicing', 'invoiced']))
        <div class="custom-control custom-checkbox checkbox-default">
            <input id="form-control-documents-{{ $item->id }}" class="custom-control-input" name="d[]" type="checkbox" value="{{ $item->id }}" data-document-xml>
            <label for="form-control-documents-{{ $item->id }}" class="custom-control-label">&nbsp;</label>
        </div>
            @else
        <span>&nbsp;</span>
        @endif
    </td>
    @endif
    <td>{{ $item->id }}</td>
    <td>{{ $item->date_of_order->format('d.m.Y.') }}</td>
    <td>{{ is_null($item->rClient) ? '-' : $item->rClient->full_name }}</td>
    <td>
        <div class="chip" style="background-color: {{ $item->rType->background_color }}">
            <div class="chip-body">
                <div class="chip-text" style="color: {{ $item->rType->color }}">{{ $item->rType->name }}</div>
            </div>
        </div>
    </td>
    <td>{{ format_price($item->total_discounted_value, 2) }} {{ $item->currency }}</td>
    @if(userIsClient())
    <td>{{ $item->rDocumentProduct->sum('total_loyalty_points') }}</td>
    @endif
    <td>
        @if($item->isOrder() && $item->is_payed)
        <div class="chip chip-success">
            <div class="chip-body">
                <div class="chip-text">Plaćeno</div>
            </div>
        </div>
        <br><small>{{ $item->payed_at->format('d.m.Y. \u H:i') }}</small>
        @else
        <div class="chip" style="background-color: {{ $item->rStatus->background_color }}">
            <div class="chip-body">
            <div class="chip-text" style="color: {{ $item->rStatus->color }}">{{ $item->rStatus->name }}</div>
            </div>
        </div>
        @endif
        @if(!userIsClient() && ($item->fiscal_receipt_no != ''))
        <div class="chip" style="background-color: {{ $item->rSyncStatus->background_color }}">
            <div class="chip-body">
                <div class="chip-text" style="color: {{ $item->rSyncStatus->color }}">{{ $item->rSyncStatus->name }}</div>
            </div>
        </div>
        @endif
    </td>
    @if(userIsAdmin() || userIsClient() || userIsSalesman())
    <td class="td-tracking">
        @if($item->isOrder() && !in_array($item->status, ['reversed']) && !is_null($item->rExpressPost))
        <a href="{{ $item->public_url }}" class="btn {{ is_null($item->rExpressPost->viewed_at) ? 'btn-dark' : 'btn-success' }}" target="_blank">Code</a>
        @else
        <span>-</span>
        @endif
    </td>
    @endif
    <td class="td-actions">
        @if(userIsClient())
        <a href="javascript:" title="Kreirao: {{ $item->rCreatedBy->rPerson->name }}" data-tooltip><i class="feather icon-user"></i></a>
        @endif
        @if(ScopedDocument::exist())
        
        @else
            @if(can('create-document') && in_array($item->status, ['draft']))
        <a href="{{ route('document.open', [$item->id]) }}" title="{{ trans('document.actions.open') }}" data-tooltip data-scoped-document-open><i class="feather icon-file-text"></i></a>
            @endif
        @endif
        @can('create-document')
        <a href="{{ route('document.copy', [$item->id]) }}" title="{{ trans('document.actions.copy') }}" data-tooltip data-document-copy data-text="{{ trans('skeleton.copy_msg') }}"><i class="feather icon-copy"></i></a>
        @endcan
        @if($item->canBeReversed())
        <a href="{{ route('document.reverse', [$item->id]) }}" title="{{ trans('document.actions.return') }}" data-tooltip data-document-reverse data-text="{{ trans('skeleton.return_msg') }}"><i class="feather icon-rotate-ccw"></i></a>
        @endif
        @if($item->canBeCanceled())
        <a href="{{ route('document.status.change') }}" title="{{ trans('document.actions.cancel') }}" data-tooltip data-document-cancel data-text="{{ trans('skeleton.cancel_msg') }}" data-id="{{ $item->id }}"><i class="feather icon-alert-circle"></i></a>
        @endif
        @can('view-document')
        <a href="{{ route('document.show', [$item->id]) }}" title="{{ trans('document.actions.show') }}" data-tooltip><i class="feather icon-external-link"></i></a>
        @endcan
        @if(!ScopedDocument::exist() && can('delete-document'))
        <a href="javascript:" class="delete-link" title="{{ trans('document.actions.destroy') }}" data-tooltip data-text="{{ trans('skeleton.delete_msg') }}" data-action="{{ route('document.destroy', [$item->id]) }}"><i class="feather icon-trash-2"></i></a>
        @endif
    </td>
</tr>
