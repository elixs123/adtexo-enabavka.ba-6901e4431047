<div class="modal-body">
    <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
    <hr>
    <div class="table-responsive-lg">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <th>#</th>
                <th>{{ trans('document.data.client_id') }}</th>
                <th>Label</th>
                <th>Pickup</th>
            </thead>
            <tbody>
                @foreach($documents as $document)
                <tr>
                    <td>{{ $document->id }}</td>
                    <td>
                        @if(is_null($document->client_id))
                        <span>-</span>
                        @else
                        <strong>{{ $document->rClient->full_name }}</strong>
                        @endif
                    </td>
                    <td><a href="{{ asset($document->rExpressPost->pdf_label_path) }}" title="Preuzmi Label PDF" target="_blank">Label PDF</a></td>
                    <td><a href="{{ asset($document->rExpressPost->pdf_pickup_path) }}" title="Preuzmi Pickup PDF" target="_blank">Pickup PDF</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-default" type="button" data-dismiss="modal">{{ trans('skeleton.actions.close') }}</button>
</div>
