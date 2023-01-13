<div class="dropdown">
    <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings"></i></button>
    <div class="dropdown-menu dropdown-menu-right p-0">
        @if(ScopedDocument::exist())
        @if(ScopedDocument::id() == $document->id)
        <a class="dropdown-item" href="{{ route('document.close') }}" data-scoped-document-close>{{ trans('document.actions.close') }}</a>
        @if(($document->status == 'draft'))
        <a class="dropdown-item" href="{{ ScopedDocument::isOrder() ? route('cart.index') : route('document.draft.complete') }}" data-scoped-document-complete>{{ trans('document.actions.complete.'.$document->type_id) }}</a>
        @endif
        @endif
        @else
        @if(in_array($document->status, ['draft']))
        <a class="dropdown-item" href="{{ route('document.open', [$document->id]) }}" data-tooltip data-scoped-document-open>{{ trans('document.actions.open') }}</a>
        @endif
        @if(can('create-document'))
        <a class="dropdown-item" href="{{ route('document.copy', [$document->id]) }}" data-document-copy data-text="{{ trans('skeleton.copy_msg') }}">{{ trans('document.actions.copy') }}</a>
        @if(userIsClient())
        <a class="dropdown-item" href="{{ route('document.create', ['type_id' => 'order']) }}" data-toggle="modal" data-target="#form-modal1">{{ trans('document.actions.new.order') }}</a>
        @else
        @php $client_document_types = $document->rClient->getDocumentTypes(); @endphp
        @foreach(trans('document.actions.create') as $key => $value)
        @if(isset($client_document_types[$key]))
        <a class="dropdown-item" href="{{ route('document.create', ['type_id' => $key, 'client_id' => $document->client_id]) }}" data-toggle="modal" data-target="#form-modal1">{{ $value }}</a>
        @endif
        @endforeach
        @endif
        @if($document->fiscal_receipt_no != null)
        <a class="dropdown-item" onclick="printInvoice({{$document->id}})">Faktura</a>
        @endif
        @if($document->fiscal_receipt_no != null)
        <a class="dropdown-item" onclick="printDuplicateReceipt({{$document->id}})">Duplikat fiskalnog</a>
        @endif
        @if($document->fiscal_receipt_no != null && $document->status != 'reversed')
        <a class="dropdown-item" onclick="printCancellationReceipt({{$document->id}})">Storniraj</a>
        @endif
        @endif
        @endif
        @if($document->fiscal_receipt_no != null)
        <a class="dropdown-item" onclick="printInvoice({{$document->id}})">Faktura</a>
        @endif
        @if($document->fiscal_receipt_no != null)
        <a class="dropdown-item" onclick="printDuplicateReceipt({{$document->id}})">Duplikat fiskalnog</a>
        @endif
        @if($document->fiscal_receipt_no != null && $document->status != 'reversed')
        <a class="dropdown-item" onclick="printCancellationReceipt({{$document->id}})">Storniraj</a>
        @endif
    </div>
</div>