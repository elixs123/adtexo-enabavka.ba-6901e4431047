<div class="modal-body">
    <p><strong>{{ $document->full_name }}</strong></p>
    <hr>
    <div class="row">
        <div class="col-12">
            {!! VuexyAdmin::locked('tracking-d', null,  $document->public_url, [], $document->rType->name.' '.$document->id.'/'.$document->created_at->format('Y')) !!}
        </div>
        <div class="col-12">
            {!! VuexyAdmin::locked('tracking-c', null,  $client->public_url, [], $client->name) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-default" type="button" data-dismiss="modal">{{ trans('skeleton.actions.close') }}</button>
</div>
