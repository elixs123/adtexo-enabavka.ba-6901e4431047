{!! Form::open(['url' => route('document.status.change'), 'method' => 'post', 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-document-express-post'), 'data-callback' => 'warehouseRemoveDocuments']) !!}
    {!! Form::hidden('s', $status) !!}
    {!! Form::hidden('t', 'order') !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
        <hr>
        <div class="row">
            @foreach($documents as $document)
            <input type="hidden" name="d[]" value="{{ $document }}">
            @endforeach
            @if($status == 'express_post')
            <div class="col-12">
                <ul class="list-group mb-1">
                    @foreach(config('express_post.countries.'.scopedStock()->priceCountryId()) as $post_type)
                    <li class="list-group-item">
                        <span class="vs-radio-con vs-radio-primary py-25">
                            <input type="radio" name="express_post_type" value="{{ $post_type }}" required checked>
                            <span class="vs-radio">
                                <span class="vs-radio--border"></span>
                                <span class="vs-radio--circle"></span>
                            </span>
                            <span class="ml-50"><strong>{{ config('express_post.types.'.$post_type) }}</strong></span>
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-12">
                {!! VuexyAdmin::textarea('note_for_express_post', '-', ['maxlength' => 150, 'class' => 'form-control', 'rows' => 2], 'Napomena preuzimanje') !!}
            </div>
            @elseif($status == 'retrieved')
            <div class="col-12">
                {!! VuexyAdmin::text('takeover_name', null, ['maxlength' => 100, 'required', 'minlength' => 2], trans('person.data.name')) !!}
            </div>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal">{{ trans('skeleton.actions.cancel') }}</button>
        @if(count($documents))
        <button class="btn btn-success" type="submit">{{ trans('skeleton.actions.submit') }}</button>
        @endif
    </div>
{!! Form::close() !!}

<script>
    $(document).ready(function () {
        App.validate('.{{ $form_class }}', {
            submitHandler: function(form) {
                AjaxForm.init('.{{ $form_class }}');
            }
        });
    });
</script>
