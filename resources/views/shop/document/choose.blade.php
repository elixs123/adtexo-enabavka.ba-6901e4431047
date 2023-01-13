{!! Form::open(['url' => $form_url, 'method' => $method, 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-choose-document'), 'data-callback' => isset($callback) ? $callback : 'documentReload']) !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong>@if(can('create-document') && !ScopedDocument::exist() && !userIsClient()) {{ trans('skeleton.or') }} <a class="text-uppercase text-bold-600" href="{{ route('document.create', ['type_id' => 'preorder', 'callback' => 'shopDocumentCreated']) }}" data-toggle="modal" data-target="#form-modal2">{{ trans('document.actions.create.preorder') }}</a>@endif</p>
        <hr>
        @if($items->count() > 0)
        <ul class="list-group mb-1">
            @foreach($items as $item)
            <li class="list-group-item">
                <span class="vs-radio-con vs-radio-primary py-25">
                    <input type="radio" name="document_id" value="{{ $item->id }}" data-choose-document>
                    <span class="vs-radio">
                        <span class="vs-radio--border"></span>
                        <span class="vs-radio--circle"></span>
                    </span>
                    <span class="ml-50"><strong>{{ $item->rType->name }}</strong> #{{ $item->id }} / <strong>{{ format_price($item->subtotal) }} {{ $item->currency }}</strong> / {{ $item->rClient->full_name }} / {{ $item->created_at->format('d.m.Y. H:i') }} @if(userIsAdmin() && !is_null($item->rCreatedBy))<small> / {{ $item->rCreatedBy->email }}</small>@endif</span>
                </span>
            </li>
            @endforeach
        </ul>
        @else
        <div class="col-12">
            <div class="no-results show">
                <h5>{{ trans('skeleton.no_results') }}</h5>
            </div>
        </div>
        @endif
    </div>
    <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal">{{ trans('skeleton.actions.cancel') }}</button>
    </div>
{!! Form::close() !!}

<script>
    $(document).ready(function () {
        App.validate('.{{ $form_class }}', {
            submitHandler: function(form) {
                AjaxForm.init('.{{ $form_class }}');
            }
        });
        
        $('input[data-choose-document]').change(function() {
            $('.{{ $form_class }}').submit();
        });
    });
</script>
