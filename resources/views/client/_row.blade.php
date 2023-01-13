<tr id="row{{ $item->uid }}">
    <td @if($item->is_headquarter) class="table-primary" title="{{ trans('client.vars.subtypes.headquarter') }}" data-tooltip @endif>@if($item->is_headquarter)<span class="badge badge-primary"><span class="feather icon-bookmark"></span></span> @endif{{ $item->full_name }}
	@if(empty($item->code) && $item->type_id == 'business_client') <span title="Šifra nije upisana." style="color: red" class="feather icon-alert-circle" data-tooltip></span> @endif
	@if($item->payment_type == 'advance_payment') <span title="Avansno plaćanje" style="color: red" class="feather icon-play" data-tooltip></span>@endif
	</td>
    <td>
        @if($item->latitude && $item->longitude)
        <a href="https://www.google.com/maps/search/?api=1&query={{ $item->latitude }},{{ $item->longitude }}" target="_blank" rel="noopener">{{ $item->full_address }}</a>
            @else
        <span>{{ $item->full_address }}</span>
        @endif
    </td>
    <td>
        @if($item->phone)
        <a href="tel:{{ $item->phone }}">{{ $item->phone }}</a>
        @else
        <span>-</span>
        @endif
    </td>
    <td><span class="badge badge-info text-uppercase">{{ $item->rType->name }}</span></td>
    <td><div class="bullet bullet-sm" style="background-color: {{ $item->rStatus->background_color }};" title="{{ $item->rStatus->name }}" data-tooltip></div></td>
    <td class="td-actions">
        @if($item->is_location && (can('view-document') || can('create-document')))
        <div class="btn-group">
            <div class="dropdown">
                <a href="javascript:" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="{{ trans('client.actions.options') }}" data-tooltip></a>
                <div class="dropdown-menu dropdown-menu-right">
                    @if(can('create-document') && !ScopedDocument::exist()) @php $client_document_types = $item->getDocumentTypes(); @endphp
                        @foreach(trans('document.actions.create') as $key => $value)
                            @if(isset($client_document_types[$key]))
                    <a class="dropdown-item" href="{{ route('document.create', ['type_id' => $key, 'client_id' => $item->id]) }}" data-toggle="modal" data-target="#form-modal1">{{ $value }}</a>
                            @endif
                        @endforeach
                    @endif
                    @if(can('view-document'))
                    <a class="dropdown-item" href="{{ route('document.index', ['type_id' => 'order', 'client_id' => $item->id, 'filters' => 1]) }}" data-loader>{{ trans('document.actions.last.order') }}</a>
                    <a class="dropdown-item" href="{{ route('document.index', ['type_id' => 'preorder', 'client_id' => $item->id, 'filters' => 1]) }}" data-loader>{{ trans('document.actions.last.preorder') }}</a>
                    @endif
                </div>
            </div>
        </div>
        @endif
        @can('edit-client')
        <a data-toggle="modal" data-target="#form-modal1" title="{{ $item->is_headquarter ? trans('client.actions.edit') : trans('client.actions.edit_location') }}" data-href="{{ route('client.edit', [$item->id]) }}" data-tooltip><i class="feather icon-edit-1"></i></a>
        @endcan
        @if(!($item->is_headquarter && $item->is_location))
        <a data-toggle="modal" data-target="#form-modal1" data-href="{{ route('client.create', ['parent_id' => $item->is_location ? $item->parent_id : $item->id]) }}" title="{{ trans('client.actions.create_location') }}" data-tooltip><i class="feather icon-map-pin"></i></a>
        @endif
        @can('view-client')
        <a data-toggle="modal" data-target="#form-modal1" data-href="{{ route('client.show', [$item->id]) }}" title="{{ trans('client.actions.show') }}" data-tooltip><i class="feather icon-external-link"></i></a>
        @endcan
        @if(can('login-as') && !is_null($item->rResponsiblePerson) && !is_null($item->rResponsiblePerson->user_id))
        <a href="{{ url('/user/login-as/' . $item->rResponsiblePerson->user_id) }}" title="{{ trans('user.actions.login_as') }}" data-tooltip><i class="feather icon-user-check"></i></a>
        @endif
    </td>
</tr>
